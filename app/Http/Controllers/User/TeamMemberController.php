<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\AdminTransferMail;
use App\Mail\RegistrationVerificationMail;
use App\Mail\TeamDissolveCodeMail;
use App\Mail\TeamDissolvedMail;
use App\Mail\TeamInvitationMail;
use App\Models\Address;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Wallet;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeamMemberController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    // Get team members for logged-in user's team
    public function getTeamMembers(Request $request)
    {

        $team = Team::where('id', Auth::user()->team_id)
            ->with(['teamMembers' => function ($query) {
                $query->where('status', '!=', 'deactivated')
                    ->orderBy('role', 'desc') // admin first
                    ->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        $address = Address::where('id', $team->address_id)
            ->select('id', 'user_id', 'amount') // Select only needed fields from address
            ->with(['user' => function ($query) {
                $query->select('id', 'account_goal'); // Select only needed fields from user
            }])
            ->first();
        return response()->json([
            'team'        => $team,
            'totalAmount' => $address,
            //   'members' => $team->teamMembers
        ]);
    }

    // Add new member with percentage redistribution
    public function addMember(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:team_members,email',
            'percentage'          => 'required|numeric|min:0|max:100',
            'amount'              => 'required|numeric|min:0',
            'members_percentages' => 'required|array', // Array of existing members with new percentages
        ]);

        // $team = Team::where('user_id', Auth::id())->firstOrFail();
        $team = Team::where('id', Auth::user()->team_id)
            ->with(['creator', 'teamMembers' => function ($query) {
                $query->where('status', '!=', 'deactivated')
                    ->orderBy('role', 'desc') // admin first
                    ->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        // Check team size limit
        $currentMembersCount = $team->teamMembers()
            ->where('status', '!=', 'deactivated')
            ->count();

        // if ($currentMembersCount >= 4) {
        //     return response()->json([
        //         'message' => 'Maximum team size (4) reached',
        //     ], 422);
        // }

        // Validate total percentage equals 100
        $totalPercentage = $request->percentage +
        collect($request->members_percentages)->sum('percentage');

        if (abs($totalPercentage - 100) > 0.01) { // Using small epsilon for floating point comparison
            return response()->json([
                'message' => 'Total percentage must equal 100%',
            ], 422);
        }

        DB::transaction(function () use ($request, $team) {
            // Update existing members' percentages
            foreach ($request->members_percentages as $memberUpdate) {
                TeamMember::where('uuid', $memberUpdate['uuid'])
                    ->where('team_id', $team->id)
                    ->update([
                        'percentage' => $memberUpdate['percentage'],
                        'amount'     => $memberUpdate['amount'],
                    ]);
            }

            // Create new member
            $member = $team->teamMembers()->create([
                'uuid'       => Str::uuid(),
                'name'       => $request->name,
                'email'      => $request->email,
                'percentage' => $request->percentage,
                'amount'     => $request->amount,
                'status'     => 'pending',
                'role'       => 'member',
            ]);

            // Create notification
            $this->notifyTeamMembers($team, 'member_added', [
                'member_name' => $member->name,
                'percentage'  => $member->percentage,
            ]);

            // Send invitation email
            $teamCreator = $team->creator;
            $teamAddress = $team->address;
            $this->sendTeamInvitationEmail($member, $team, $teamCreator, $teamAddress);

        });

        $freshTeam = Team::where('id', Auth::user()->team_id)
            ->with(['teamMembers' => function ($query) {
                $query->where('status', '!=', 'deactivated')
                    ->orderBy('role', 'desc')
                    ->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        return response()->json([
            'message' => 'Team member added successfully',
            'team'    => $freshTeam,
            'status'  => 'success',
        ]);
    }

    private function sendTeamInvitationEmail($member, $team, $teamCreator, $teamAddress)
    {
        $details = [
            'account_goal' => $teamCreator->account_goal,
            'name'         => $member->name,
            'admin_name'   => "{$teamCreator->first_name} {$teamCreator->last_name}",
            'team_id'      => $team->uuid,
            'address'      => $teamAddress->name,
            'role'         => $teamCreator->account_goal === 'rent' ? 'Co-Applicant' : 'Co-Owner',
            'token'        => $member->invitation_token,
        ];

        // Update your routes to include the token
        Mail::to($member->email)->send(new TeamInvitationMail($details));
    }

    public function resendInvite2(Request $request)
    {
        $request->validate([
            'member_uuid' => 'required|exists:team_members,uuid',
        ]);

        $member = TeamMember::where('uuid', $request->member_uuid)
            ->where('status', 'pending')
            ->with(['team.creator', 'team.address'])
            ->firstOrFail();

        // Generate new invitation token and expiration
        $member->update([
            'invitation_token'      => Str::random(32),
            'invitation_expires_at' => Carbon::now()->addDays(7),
        ]);

        $teamCreator = $member->team->creator;
        $team        = $member->team;
        $teamAddress = $member->team->address;

        // Send email to co-applicant
        $this->sendTeamInvitationEmail($member, $team, $teamCreator, $teamAddress);

        return response()->json([
            'message' => 'Invite resent successfully',
        ]);
    }
    public function resendInvite(Request $request)
    {
        $request->validate([
            'member_uuid' => 'required|string',
        ]);

        try {
            $member = TeamMember::where('uuid', $request->member_uuid)
                ->where('status', 'pending')
                ->with(['team.creator', 'team.address'])
                ->firstOrFail();

            // Update next invite time
            // Generate new invitation token and expiration
            $member->update([
                'invitation_token'      => Str::random(32),
                'invitation_expires_at' => Carbon::now()->addDays(7),
            ]);

            $teamCreator = $member->team->creator;
            $team        = $member->team;
            $teamAddress = $member->team->address;

            // Send email to co-applicant
            $this->sendTeamInvitationEmail($member, $team, $teamCreator, $teamAddress);

            return response()->json([
                'message' => 'Invite resent successfully',
                'status'  => 'success',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error resending invite: ' . $e->getMessage(),
                'status'  => 'error',
            ], 500);
        }
    }

    public function acceptInvite(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $member = TeamMember::where('invitation_token', $request->token)
            ->where('status', 'pending')
            ->where('invitation_expires_at', '>', Carbon::now())
            ->firstOrFail();

        $member->update([
            'status'                => 'accepted',
            'invitation_token'      => null,
            'invitation_expires_at' => null,
        ]);

        return response()->json([
            'message' => 'Invitation accepted successfully',
        ]);
    }

    public function verifyInvitationToken($token)
    {
        try {
            $member = TeamMember::where('invitation_token', $token)
                ->with(['team.creator', 'team.address'])
                ->first();

            if (! $member) {
                return response()->json([
                    'valid'   => false,
                    'message' => 'Invalid invitation link',
                ], 404);
            }

            // Check if invitation is already used
            // if ($member->status !== 'pending') {
            //     return response()->json([
            //         'valid'   => false,
            //         'message' => 'This invitation has already been used',
            //     ], 400);
            // }
            // Check specific status conditions
            switch ($member->status) {
                case 'declined':
                    return response()->json([
                        'valid'   => false,
                        'message' => 'This invitation has been declined',
                        'status'  => 'declined',
                    ], 400);
                case 'accepted':
                    return response()->json([
                        'valid'   => false,
                        'message' => 'This invitation has already been accepted',
                        'status'  => 'accepted',
                    ], 400);
                case 'pending':
                    // Continue with the valid invitation process
                    break;
                default:
                    return response()->json([
                        'valid'   => false,
                        'message' => 'Invalid invitation status',
                        'status'  => $member->status,
                    ], 400);
            }

            // Check if invitation has expired
            if ($member->invitation_expires_at && $member->invitation_expires_at < Carbon::now()) {
                return response()->json([
                    'valid'   => false,
                    'message' => 'This invitation has expired',
                    'status'  => 'expired',
                ], 400);
            }

            return response()->json([
                'valid' => true,
                'data'  => [
                    'name'             => $member->name,
                    'email'            => $member->email,
                    'team_name'        => $member->team->name,
                    'property_address' => $member->team->address->name,
                    'total_amount'     => $member->team->address->amount,
                    'percentage'       => $member->percentage,
                    'monthly_payment'  => $member->amount,
                    'role'             => $member->team->creator->account_goal,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid'   => false,
                'message' => 'An error occurred while verifying the invitation',
            ], 500);
        }
    }

    public function completeRegistration(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'first_name'     => 'required|string|max:255',
                'middle_name'    => 'nullable|string|max:255',
                'last_name'      => 'required|string|max:255',
                'email'          => [
                    'required',
                    'email',
                    'unique:users,email',
                    function ($attribute, $value, $fail) use ($request) {
                        $member = TeamMember::where('invitation_token', $request->token)
                            ->where('status', 'pending')
                            ->first();

                        if ($member && $member->email !== $value) {
                            $fail('Please use the email address the invitation was sent to.');
                        }
                    },
                ],
                'phone'          => [
                    'required',
                    'string',
                    'regex:/^\d{10}$/',
                ],
                'password'       => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d)/',
                    'confirmed',
                ],
                'terms_accepted' => 'required|boolean|accepted',
            ], [
                'email.unique' => 'The email address is already registered.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();

            $member = TeamMember::where('invitation_token', $request->token)
                ->where('status', 'pending')
                ->with(['team.creator'])
                ->first();

            if (! $member) {
                return response()->json([
                    'message' => 'Invalid or expired invitation',
                ], 404);
            }

            // Check if email matches invitation
            if ($member->email !== $validated['email']) {
                return response()->json([
                    'message' => 'Email address does not match the invitation',
                    'errors'  => ['email' => ['Please use the email address the invitation was sent to']],
                ], 422);
            }

            DB::beginTransaction();

            // Create user with pending status
            $user = User::create([
                'first_name'               => $validated['first_name'],
                'middle_name'              => $validated['middle_name'],
                'last_name'                => $validated['last_name'],
                'email'                    => $validated['email'],
                'phone'                    => $validated['phone'],
                'password'                 => $validated['password'],
                'status'                   => 'pending', // Set initial status as pending
                'team_id'                  => $member->team->id,
                'is_team'                  => true,
                'account_type'             => $member->team->creator->account_type,
                'payment_setup'            => $member->team->creator->payment_setup,
                'account_goal'             => $member->team->creator->account_goal,
                'email_verification_token' => Str::random(64),
            ]);

            // Update member
            $member->update([
                'status'                => 'accepted',
                'user_id'               => $user->id,
                'invitation_token'      => null,
                'invitation_expires_at' => null,
                'name'                  => "{$validated['first_name']} {$validated['last_name']}",
                'email'                 => $validated['email'],
            ]);

            // Create wallet
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);

            // Send verification email
            Mail::to($user->email)->send(new RegistrationVerificationMail($user));
            // dump($user, $mail);
            // dd(new RegistrationVerificationMail($user), $mail);

            DB::commit();

            // Log successful registration
            \Log::info('User registered successfully, pending verification', [
                'user_id' => $user->id,
                'team_id' => $member->team_id,
            ]);

            return response()->json([
                'message' => 'Registration completed successfully. Please check your email for verification instructions.',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Registration error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Registration failed. Please try again later.',
            ], 500);
        }
    }

    public function declineInvite($token)
    {
        try {

            DB::beginTransaction();
            // Find the member with relationships
            $member = TeamMember::where('invitation_token', $token)
                ->with(['team.creator'])
                ->first();

            // Comprehensive validation
            if (! $member) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid invitation link',
                ], 404);
            }

            if ($member->status !== 'pending') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'This invitation has already been ' . $member->status,
                ], 400);
            }

            if ($member->invitation_expires_at < Carbon::now()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'This invitation has expired',
                ], 400);
            }

            // Update member status
            $member->update([
                'status'                => 'declined',
                'invitation_token'      => null,
                'invitation_expires_at' => null,
            ]);

            DB::commit();

            // Optional: Notify team creator
            if ($member->team && $member->team->creator) {
                // You can implement notification here
                // Notification::send($member->team->creator, new TeamInviteDeclinedNotification($member));
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Invitation declined successfully',
            ]);

        } catch (\Exception $e) {
            report($e); // Log the error
            return response()->json([
                'status'  => 'error',
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }

    public function declineInvitation($token)
    {
        try {
            DB::beginTransaction();

            $member = TeamMember::where('invitation_token', $token)
                ->where('status', 'pending')
                ->first();

            if (! $member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired invitation token',
                ], 404);
            }

            // Update member status to declined
            $member->update([
                'status'      => 'declined',
                // 'invitation_token' => null,
                'declined_at' => now(),
            ]);

            // Log the decline action
            \Log::info('Team invitation declined', [
                'team_id' => $member->team_id,
                'email'   => $member->email,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invitation declined successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error declining team invitation', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to decline invitation',
            ], 500);
        }
    }

    // Update admin and redistribute percentages
    public function changeAdmin(Request $request)
    {
        $request->validate([
            'new_admin_uuid'      => 'required|exists:team_members,uuid',
            'members_percentages' => 'required|array',
        ]);

        $team = Team::where('user_id', Auth::id())->firstOrFail();

        // Validate total percentage equals 100
        $totalPercentage = collect($request->members_percentages)->sum('percentage');
        if (abs($totalPercentage - 100) > 0.01) {
            return response()->json([
                'message' => 'Total percentage must equal 100%',
            ], 422);
        }

        DB::transaction(function () use ($request, $team) {
            // Remove old admin role
            $team->teamMembers()
                ->where('role', 'admin')
                ->update(['role' => 'member']);

            // Set new admin
            TeamMember::where('uuid', $request->new_admin_uuid)
                ->where('team_id', $team->id)
                ->update(['role' => 'admin']);

            // Update all percentages
            foreach ($request->members_percentages as $memberUpdate) {
                TeamMember::where('uuid', $memberUpdate['uuid'])
                    ->where('team_id', $team->id)
                    ->update([
                        'percentage' => $memberUpdate['percentage'],
                        'amount'     => $memberUpdate['amount'],
                    ]);
            }
        });

        return response()->json([
            'message' => 'Admin changed successfully',
            'team'    => $team->fresh(['teamMembers']),
        ]);
    }

    // Remove member and redistribute percentages
    public function removeMember(Request $request)
    {
        $request->validate([
            'member_uuid'         => 'required|exists:team_members,uuid',
            'members_percentages' => 'required|array', // Remaining members with new percentages
        ]);

        // First check if user is admin
        $isAdmin = TeamMember::where('team_id', Auth::user()->team_id)
            ->where('user_id', Auth::id())
            ->where('role', 'admin')
            ->where('status', '!=', 'deactivated')
            ->exists();

        if (! $isAdmin) {
            return response()->json(['message' => 'Unauthorized. Only team admins can perform this action.'], 403);
        }

        // If admin, proceed with team query
        $team = Team::where('id', Auth::user()->team_id)
            ->with(['teamMembers' => function ($query) {
                $query->where('status', '!=', 'deactivated')
                    ->orderBy('role', 'desc')
                    ->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        // Validate total percentage equals 100
        $totalPercentage = collect($request->members_percentages)->sum('percentage');
        if (abs($totalPercentage - 100) > 0.01) {
            return response()->json([
                'message' => 'Total percentage must equal 100%',
            ], 422);
        }

        DB::transaction(function () use ($request, $team) {
            // Get member details before removal
            $memberToRemove = TeamMember::where('uuid', $request->member_uuid)
                ->with('user:id,first_name,last_name')
                ->first();

            if ($memberToRemove) {
                $user = User::where('id', $memberToRemove->user_id)
                    ->update([
                        'team_id' => null,
                        'is_team' => false,
                    ]);
            } else {
                // Handle case where member is not found
                return response()->json(['message' => 'Team member not found'], 404);
            }

            if (! $memberToRemove) {
                throw new \Exception('Member not found');
            }

            // Construct full name
            $memberName = $memberToRemove->user
            ? $memberToRemove->user->first_name . ' ' . $memberToRemove->user->last_name
            : $memberToRemove->name;

            // Deactivate member
            TeamMember::where('uuid', $request->member_uuid)
                ->where('team_id', $team->id)
                ->update(['status' => 'deactivated']);

            // Update remaining members' percentages
            foreach ($request->members_percentages as $memberUpdate) {
                TeamMember::where('uuid', $memberUpdate['uuid'])
                    ->where('team_id', $team->id)
                    ->update([
                        'percentage' => $memberUpdate['percentage'],
                        'amount'     => $memberUpdate['amount'],
                    ]);
            }

            // Get current admin info
            $currentAdmin = Auth::user();
            $adminName    = trim($currentAdmin->first_name . ' ' . $currentAdmin->last_name);

            // Notify remaining team members about the removal
            $activeMembers = $team->teamMembers()
                ->where('status', 'accepted')
                ->where('uuid', '!=', $request->member_uuid)
                ->with(['user:id,first_name,last_name,email'])
                ->get();

            foreach ($activeMembers as $member) {
                $this->notificationService->create(
                    'Team Member Removed',
                    "{$memberName} has been removed from the team by {$adminName}.",
                    'general',
                    [
                        'team_id'        => $team->id,
                        'removed_member' => $memberName,
                        'action_by'      => $adminName,
                        'removed_at'     => now()->toDateTimeString(),
                    ],
                    $member->user->id// Specific user ID for notification
                );
            }

            //Send an email that the user has been removed
        });

        $freshTeam = Team::where('id', Auth::user()->team_id)
            ->with(['teamMembers' => function ($query) {
                $query->where('status', '!=', 'deactivated')
                    ->orderBy('role', 'desc')
                    ->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        return response()->json([
            'message' => 'Member removed successfully',
            'team'    => $freshTeam,
            'status'  => 'success',
        ]);
    }

    public function transferAdmin(Request $request)
    {
        $request->validate([
            'new_admin_uuid' => 'required|exists:team_members,uuid',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Get current user's team
                $team = Team::where('id', Auth::user()->team_id)->firstOrFail();

                // Validate new admin exists and belongs to same team
                $newAdmin = TeamMember::where([
                    'uuid'    => $request->new_admin_uuid,
                    'team_id' => $team->id,
                    'status'  => 'accepted',
                ])->whereHas('user')->with(['user', 'team'])->firstOrFail();

                // Validate current admin
                $currentAdmin = TeamMember::where([
                    'team_id' => $team->id,
                    'role'    => 'admin',
                    'user_id' => Auth::user()->id,
                    'status'  => 'accepted',
                ])->firstOrFail();

                // Update roles
                $currentAdmin->update(['role' => 'member']);
                $newAdmin->update(['role' => 'admin']);
                $team->update(['user_id' => $newAdmin->user_id]);

                // Notifications
                $this->notifyTeamMembers($team, 'admin_changed', [
                    'member_name' => $newAdmin->user->full_name,
                    'action_by'   => Auth::user()->full_name,
                ]);

                Mail::to($newAdmin->user->email)->send(new AdminTransferMail($newAdmin));
            });

            return response()->json([
                'message' => 'Admin role transferred successfully',
                'success' => true,
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Invalid team member or permissions',
                'success' => false,
            ], 404);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'message' => 'Failed to transfer admin role',
                'success' => false,
            ], 500);
        }
    }

    public function dissolveTeam(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $storedCode = Cache::get('team_dissolve_code_' . Auth::id());

        if (! $storedCode || $storedCode !== $request->verification_code) {
            return response()->json([
                'message' => 'Invalid verification code',
                'success' => false,
            ], 422);
        }

        try {
            DB::transaction(function () {
                $team = Team::where('id', Auth::user()->team_id)
                    ->with(['teamMembers.user'])
                    ->firstOrFail();

                $members = $team->teamMembers()
                    ->where('status', '!=', 'deactivated')
                    ->with('user')
                    ->get();

                // Notify members
                $this->notifyTeamMembers($team, 'team_dissolved', [
                    'admin_name' => Auth::user()->full_name,
                ]);

                // Send emails
                foreach ($members as $member) {
                    Mail::to($member->user->email)->send(new TeamDissolvedMail($team));
                }

                // Deactivate team and members
                $team->teamMembers()->update(['status' => 'deactivated']);
                $team->update(['status' => 'dissolved']);
            });

            Cache::forget('team_dissolve_code_' . Auth::id());

            return response()->json([
                'message' => 'Team dissolved successfully',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to dissolve team',
                'success' => false,
            ], 500);
        }
    }

    public function sendDissolveCode()
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('team_dissolve_code_' . Auth::id(), $code, now()->addMinutes(30));

        Mail::to(Auth::user()->email)->send(new TeamDissolveCodeMail($code));

        return response()->json(['message' => 'Verification code sent']);
    }

    public function updatePercentages(Request $request)
    {
        $request->validate([
            'members_percentages'              => 'required|array',
            'members_percentages.*.uuid'       => 'required|string',
            'members_percentages.*.percentage' => 'required|numeric|min:0|max:100',
            'members_percentages.*.amount'     => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalPercentage = collect($request->members_percentages)
                ->sum('percentage');

            if (abs($totalPercentage - 100) > 0.01) {
                return response()->json([
                    'message' => 'Total percentage must equal 100%',
                ], 422);
            }

            foreach ($request->members_percentages as $memberData) {
                TeamMember::where('uuid', $memberData['uuid'])
                    ->update([
                        'percentage' => $memberData['percentage'],
                        'amount'     => $memberData['amount'],
                    ]);
            }

            DB::commit();

            $team = Team::where('id', Auth::user()->team_id)
                ->with(['teamMembers' => function ($query) {
                    $query->where('status', '!=', 'deactivated')
                        ->orderBy('role', 'desc') // admin first
                        ->orderBy('created_at', 'asc');
                }])
                ->firstOrFail();

            $this->notifyTeamMembers($team, 'percentage_updated');

            // Get fresh team data
            $freshTeam = $team->fresh(['teamMembers' => function ($query) {
                $query->where('status', '!=', 'deactivated')
                    ->orderBy('role', 'desc')
                    ->orderBy('created_at', 'asc');
            }]);

            return response()->json([
                'message' => 'Percentages updated successfully',
                'team'    => $freshTeam,
                'status'  => 'success',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating percentages: ' . $e->getMessage(),
                'status'  => 'error',
            ], 500);
        }
    }

    /**
     * Send notification to all accepted team members
     */
    private function notifyTeamMembers(Team $team, string $type, array $data = [], $excludeUserIds = [])
    {
        // Get all accepted team members
        $teamMembers = $team->teamMembers()
            ->where('status', 'accepted')
            ->whereNotIn('user_id', $excludeUserIds)
            ->with(['user:id,first_name,last_name,email'])
            ->get();
        // dd($teamMembers);
        foreach ($teamMembers as $member) {
            $this->createTeamNotification($team, $type, $data, $member->user);
        }
    }

    /**
     * Send notification to a specific team member
     */
    private function notifyTeamMember(Team $team, string $type, array $data = [], $userId)
    {
        $teamMember = $team->teamMembers()
            ->where('user_id', $userId)
            ->where('status', 'accepted')
            ->with(['user:id,first_name,last_name,email'])
            ->first();

        if ($teamMember) {
            $this->createTeamNotification($team, $type, $data, $teamMember->user);
        }
    }

    private function createTeamNotification(Team $team, string $type, array $data, $user)
    {
        $title   = $this->getTeamNotificationTitle($type);
        $message = $this->getTeamNotificationMessage($type, $data, $user);

        return $this->notificationService->create(
            $title,
            $message,
            'general',
            array_merge($data, ['team_id' => $team->id]),
            $user->id
        );
    }

    /**
     * Get notification title based on type
     */
    private function getTeamNotificationTitle(string $type): string
    {
        return match ($type) {
            'member_added' => 'New Team Member Added',
            'member_removed' => 'Team Member Removed',
            'percentage_updated' => 'Contribution Percentages Updated',
            'team_dissolved' => 'Team Dissolved',
            'admin_changed' => 'Team Admin Changed',
            default => 'Team Update',
        };
    }

    /**
     * Get notification message based on type and data
     */
    private function getTeamNotificationMessage(string $type, array $data, $user): string
    {
        $memberName = $data['member_name'] ?? 'A member';
        $adminName  = $data['action_by'] ?? 'the admin';
        $percentage = $data['percentage'] ?? '0';

        return match ($type) {
            'member_added' => "{$memberName} has been added to the team with {$percentage}% contribution.",
            'member_removed' => "{$memberName} has been removed from the team by {$adminName}.",
            'percentage_updated' => "Team contribution percentages have been updated.",
            'team_dissolved' => "The team has been dissolved by {$adminName}.",
            'admin_changed' => "{$memberName} is now the team admin.",
            default => "Team information has been updated.",
        };
    }
}
