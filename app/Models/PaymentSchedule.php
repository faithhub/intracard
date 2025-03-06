<?php
namespace App\Models;

use App\Jobs\PaymentReminderJob;
use App\Jobs\TeamMemberPaymentReminderJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PaymentSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'team_id',
        'payment_type',
        'is_team_payment',
        'amount',
        'frequency',
        'address_id',
        'bill_history_id',
        'recurring_day',
        'duration_from',
        'duration_to',
        'reminder_dates',
        'status',
    ];

    protected $casts = [
        'reminder_dates' => 'array',    // Cast JSON to array
        'duration_from'  => 'datetime', // Cast JSON to array
        'duration_to'    => 'datetime', // Cast JSON to datetime
    ];

    // Automatically generate reminder dates
    public function generateRecurringDates()
    {
        $dates        = [];
        $startDate    = Carbon::parse($this->duration_from);
        $endDate      = Carbon::parse($this->duration_to);
        $recurringDay = $this->recurring_day;
        $frequency    = $this->frequency ?? 'monthly';

        $currentDate = $startDate->copy();

        // Keep track of added dates to prevent duplicates
        $addedDates = [];

        while ($currentDate->lte($endDate)) {
            if ($frequency === 'monthly') {
                // Set the recurring day for the current month
                $recurringDate = $currentDate->copy()->day($recurringDay);

                // Format the date for comparison
                $dateString = $recurringDate->toDateString();

                // Only add if it's valid, within range, and not already added
                if ($recurringDate->isValid() &&
                    $recurringDate->between($startDate, $endDate) &&
                    ! in_array($dateString, $addedDates)) {

                    $dates[]      = $dateString;
                    $addedDates[] = $dateString;
                }

                // Move to the next month
                $currentDate->addMonth()->startOfMonth();
            } elseif ($frequency === 'bi-weekly') {
                $dateString = $currentDate->toDateString();

                if (! in_array($dateString, $addedDates)) {
                    $dates[]      = $dateString;
                    $addedDates[] = $dateString;
                }

                $currentDate->addWeeks(2);
            }
        }

        return $dates;
    }

    public function generateRemindersForDate($paymentDate)
    {
        $paymentDate = Carbon::parse($paymentDate);
        $frequency   = $this->frequency ?? 'monthly'; // Default to 'monthly'

        // Fetch reminder intervals based on frequency
        $reminderIntervals = $this->getReminderDaysByFrequency($frequency);

        $reminders = [];
        foreach ($reminderIntervals as $key => $daysBefore) {
            $reminderDate    = $paymentDate->copy()->subDays($daysBefore);
            $reminders[$key] = $reminderDate->toDateString();
        }

        return $reminders;
    }

    /**
     * Fetch reminder days by frequency from the settings table.
     * @param string $frequency
     * @return array
     */
    private function getReminderDaysByFrequency(string $frequency)
    {
        // Default reminder intervals if not specified in settings
        $defaultSettings = [
            'bi-weekly' => [
                '5_days_before' => 5,
                '3_days_before' => 3,
                '2_days_before' => 2,
            ],
            'monthly'   => [
                '7_days_before' => 7,
                '5_days_before' => 5,
                '2_days_before' => 2,
            ],
        ];

        // Fetch from the settings table
        $settings = DB::table('settings')
            ->where('key', 'reminder_days_' . $frequency)
            ->value('value');

        if ($settings) {
            // Convert JSON settings into an array
            return json_decode($settings, true);
        }

        // Use default settings if no admin-specified values are found
        return $defaultSettings[$frequency] ?? [];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function generateReminders()
    {
        $reminders   = [];
        $frequency   = $this->frequency ?? 'monthly';
        $startDate   = $this->duration_from;
        $endDate     = $this->duration_to->endOfDay();
        $currentDate = Carbon::now();

        // Initialize next payment date based on frequency
        if ($frequency === 'monthly') {
            $nextPaymentDate = $startDate->copy()->day($this->recurring_day);
            if (! $nextPaymentDate->isValid()) {
                $nextPaymentDate = $nextPaymentDate->startOfMonth()->addMonth()->day($this->recurring_day);
            }
        } else { // bi-weekly
            $nextPaymentDate = $startDate->copy();
        }

        // Ensure next payment date isn't before start date
        if ($nextPaymentDate->lt($startDate)) {
            if ($frequency === 'monthly') {
                $nextPaymentDate = $nextPaymentDate->addMonth()->day($this->recurring_day);
            } else {
                $nextPaymentDate = $startDate->copy();
            }
        }

        $defaultReminderDays = $this->getReminderDaysByFrequency($frequency);
        $maxIterations       = $frequency === 'bi-weekly' ? 26 : 12;

        // Generate payment dates and reminders
        while ($nextPaymentDate->lte($endDate) && $maxIterations > 0) {
            if ($nextPaymentDate->isValid()) {
                $paymentReminders = [];

                // Always include the payment date in reminders, even if empty
                $reminders[$nextPaymentDate->toDateString()] = [];

                // Generate reminders for valid dates
                foreach ($defaultReminderDays as $reminderKey => $daysBefore) {
                    $reminderDate = $nextPaymentDate->copy()->subDays($daysBefore);

                    if ($reminderDate->gte($startDate)) {
                        $paymentReminders[$reminderKey] = $reminderDate->toDateString();

                        // Queue reminder notifications for future dates
                        if ($reminderDate->isFuture()) {
                            if ($this->team_id) {
                                TeamMemberPaymentReminderJob::dispatch(
                                    $this->user,
                                    $this,
                                    $reminderKey,
                                    Carbon::parse($reminderDate)
                                )->delay(Carbon::parse($reminderDate));
                            } else {
                                PaymentReminderJob::dispatch(
                                    $this->user,
                                    $this,
                                    $reminderKey,
                                    Carbon::parse($reminderDate)
                                )->delay(Carbon::parse($reminderDate));
                            }
                        }
                    }
                }

                // Update reminders if we have any valid reminder dates
                if (! empty($paymentReminders)) {
                    $reminders[$nextPaymentDate->toDateString()] = $paymentReminders;
                }
            }

            // Move to next payment date based on frequency
            if ($frequency === 'bi-weekly') {
                $nextPaymentDate->addWeeks(2);
            } else {
                $nextPaymentDate->addMonth();
            }

            $maxIterations--;
        }

        return $reminders;
    }

    // Add this to PaymentSchedule model
    public function verifyReminders()
    {
        $reminderDates = json_decode($this->reminder_dates, true);
        $today         = Carbon::now()->startOfDay();
        $issues        = [];

        if (empty($reminderDates)) {
            return ["No reminder dates found for this schedule"];
        }

        foreach ($reminderDates as $paymentDate => $reminders) {
            // Skip empty reminder arrays
            if (empty($reminders)) {
                continue;
            }

            try {
                $paymentCarbon = Carbon::parse($paymentDate);

                foreach ($reminders as $reminderType => $reminderDate) {
                    // Validate reminder date format
                    try {
                        $reminderCarbon = Carbon::parse($reminderDate);
                    } catch (\Exception $e) {
                        $issues[] = "Invalid reminder date format for {$paymentDate}: {$reminderDate}";
                        continue;
                    }

                    // Verify reminder is before payment date
                    if ($reminderCarbon->gte($paymentCarbon)) {
                        $issues[] = "For payment {$paymentDate}: Reminder date {$reminderDate} is not before payment date";
                    }

                    // Extract number of days from reminder type
                    if (preg_match('/(\d+)_days_before/', $reminderType, $matches)) {
                        $expectedDays = (int) $matches[1];
                        $actualDays   = $paymentCarbon->diffInDays($reminderCarbon);

                        if ($actualDays !== $expectedDays) {
                            $issues[] = "For payment {$paymentDate}: Expected {$expectedDays} days before payment, but got {$actualDays} days";
                        }
                    } else {
                        $issues[] = "Invalid reminder type format: {$reminderType}";
                    }
                }
            } catch (\Exception $e) {
                $issues[] = "Error processing payment date {$paymentDate}: " . $e->getMessage();
            }
        }

        return $issues;
    }

    public function createReminders()
{
    try {
        \Log::info('Starting createReminders for schedule', ['schedule_id' => $this->id]);
        
        // Check if required properties exist
        if (!$this->id || !$this->user_id) {
            \Log::error('Missing required properties for payment schedule', [
                'schedule_id' => $this->id ?? 'null',
                'user_id' => $this->user_id ?? 'null'
            ]);
            return [];
        }
        
        // Count existing reminders before deletion
        $existingCount = PaymentReminder::where('payment_schedule_id', $this->id)->count();
        \Log::info('Existing reminders count', ['count' => $existingCount]);
        
        // Only delete FUTURE reminders for this schedule
        $deleted = PaymentReminder::where('payment_schedule_id', $this->id)
            ->where('reminder_date', '>=', now()->startOfDay())
            ->where('status', 'pending') // Only delete pending reminders
            ->delete();
        
        \Log::info('Deleted pending future reminders', ['count' => $deleted]);

        $frequency = $this->frequency ?? 'monthly';
        $startDate = Carbon::parse($this->duration_from);
        $endDate   = Carbon::parse($this->duration_to)->endOfDay();
        
        \Log::info('Schedule details', [
            'frequency' => $frequency,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'is_team' => (bool)$this->team_id
        ]);

        // Generate payment dates based on frequency
        $paymentDates = [];
        $currentDate  = $startDate->copy();

        if ($frequency === 'monthly') {
            while ($currentDate->lte($endDate)) {
                $paymentDay  = min($this->recurring_day, $currentDate->daysInMonth);
                $paymentDate = $currentDate->copy()->day($paymentDay);

                if ($paymentDate->gte($startDate) && $paymentDate->lte($endDate)) {
                    $paymentDates[] = $paymentDate->copy();
                }

                $currentDate->addMonth();
            }
        } else { // bi-weekly
            while ($currentDate->lte($endDate)) {
                if ($currentDate->gte($startDate)) {
                    $paymentDates[] = $currentDate->copy();
                }
                $currentDate->addWeeks(2);
            }
        }
        
        \Log::info('Generated payment dates', [
            'count' => count($paymentDates),
            'dates' => array_map(function($date) { return $date->toDateString(); }, $paymentDates)
        ]);

        // Check if we have dates
        if (empty($paymentDates)) {
            \Log::error('No payment dates generated', [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'frequency' => $frequency
            ]);
            return [];
        }

        // Create reminder records for each payment date
        $reminderIntervals = $this->getReminderDaysByFrequency($frequency);
        
        \Log::info('Reminder intervals', ['intervals' => $reminderIntervals]);
        
        // Check if we have reminder intervals
        if (empty($reminderIntervals)) {
            \Log::error('No reminder intervals returned', ['frequency' => $frequency]);
            return [];
        }
        
        $createdReminders = [];

        \Log::info('Team details check', [
            'team_id' => $this->team_id,
            'team_id_type' => gettype($this->team_id),
            'team_id_empty' => empty($this->team_id),
            'is_null' => is_null($this->team_id),
            'is_team_member' => $this->user && $this->user->team_id ? true : false,
            'user_team_id' => $this->user ? $this->user->team_id : null
        ]);

        foreach ($paymentDates as $paymentDate) {
            $periodKey = Payment::generatePeriodKey($this, $paymentDate->toDateString());

            foreach ($reminderIntervals as $reminderType => $daysBefore) {
                $reminderDate = $paymentDate->copy()->subDays($daysBefore);

                // Only create reminders for future dates
                if ($reminderDate->gte(now()->startOfDay())) {
                    // For individual user (if not a team payment)
                    if (!$this->team_id) {
                        try {
                            $individualAmount = $this->amount ?? 0;
                            
                            // Default the expected charges to 0 for now
                            $expectedIndividualChargesPercentage = 0; //0%
                            $expectedIndividualCharges = ($individualAmount * $expectedIndividualChargesPercentage) / 100;
                        
                            $reminderData = [
                                'payment_schedule_id' => $this->id,
                                'user_id' => $this->user_id,
                                'payment_date' => $paymentDate->toDateString(),
                                'reminder_date' => $reminderDate->toDateString(),
                                'reminder_type' => $reminderType,
                                'period_key' => $periodKey,
                                'is_team_reminder' => false,
                                'amount' => $individualAmount,  
                                'charges' => $expectedIndividualCharges,  
                                'status' => 'pending',
                                'payment_status' => 'pending',
                            ];
                        
                            $reminder = PaymentReminder::updateOrCreate(
                                [
                                    'payment_schedule_id' => $this->id,
                                    'user_id' => $this->user_id,
                                    'payment_date' => $paymentDate->toDateString(),
                                    'reminder_type' => $reminderType,
                                ],
                                $reminderData
                            );
                            
                            if (!$reminder) {
                                \Log::error('Failed to create individual payment reminder', [
                                    'schedule_id' => $this->id,
                                    'user_id' => $this->user_id,
                                    'payment_date' => $paymentDate->toDateString(),
                                    'reminder_data' => $reminderData
                                ]);
                            } else {
                                $createdReminders[] = $reminder;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error creating individual payment reminder', [
                                'schedule_id' => $this->id,
                                'user_id' => $this->user_id,
                                'payment_date' => $paymentDate->toDateString(),
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                            // Continue with next reminder - don't let one failure stop the whole process
                            continue;
                        }
                    }
                    
                    // For team reminders if this is a team schedule
                    // For team reminders if this is a team schedule or user belongs to a team
                    if ($this->team_id || ($this->user && $this->user->team_id)) {
                    // if ($this->team_id) {

                        // Use team_id from the payment schedule or from the user
                        $effectiveTeamId = $this->team_id ?? $this->user->team_id;
                        
                        // Get all team members
                        $teamMembers = User::where('team_id', $effectiveTeamId)->get();

                        // Get all team members
                        // $teamMembers = User::where('team_id', $this->team_id)->get();

                        // Include the team owner if they're not already in the members list
                        if (!$teamMembers->contains('id', $this->user_id)) {
                            $teamMembers->push($this->user);
                        }

                        foreach ($teamMembers as $member) {
                            try {
                                // Get the team member record to find their percentage
                                $teamMember = TeamMember::where('team_id', $this->team_id)
                                    ->where('user_id', $member->id)
                                    ->first();

                                // Use the actual percentage from the team member
                                $percentage = $teamMember ? ($teamMember->percentage ?? 0) : 0;

                                // Calculate the member's portion of the payment
                                $totalAmount  = $this->amount ?? 0;
                                $memberAmount = ($totalAmount * $percentage) / 100;

                                // Default the expected charges to 0 for now
                                $expectedChargesPercentage = 0; //0%
                                $expectedCharges = ($memberAmount * $expectedChargesPercentage) / 100;

                                $teamReminderData = [
                                    'payment_schedule_id' => $this->id,
                                    'user_id' => $member->id,
                                    'team_id' => $effectiveTeamId,
                                    'payment_date' => $paymentDate->toDateString(),
                                    'reminder_date' => $reminderDate->toDateString(),
                                    'reminder_type' => $reminderType,
                                    'period_key' => $periodKey,
                                    'is_team_reminder' => true,
                                    'amount' => $memberAmount,
                                    'charges' => $expectedCharges,
                                    'status' => 'pending',
                                    'payment_status' => 'pending',
                                ];

                                $reminder = PaymentReminder::updateOrCreate(
                                    [
                                        'payment_schedule_id' => $this->id,
                                        'user_id' => $member->id,
                                        'payment_date' => $paymentDate->toDateString(),
                                        'reminder_type' => $reminderType,
                                    ],
                                    $teamReminderData
                                );
                                
                                if (!$reminder) {
                                    \Log::error('Failed to create team payment reminder', [
                                        'schedule_id' => $this->id,
                                        'user_id' => $member->id,
                                        'team_id' => $this->team_id,
                                        'payment_date' => $paymentDate->toDateString(),
                                        'reminder_data' => $teamReminderData
                                    ]);
                                } else {
                                    $createdReminders[] = $reminder;
                                }
                            } catch (\Exception $e) {
                                \Log::error('Error creating team payment reminder', [
                                    'schedule_id' => $this->id,
                                    'user_id' => $member->id,
                                    'team_id' => $this->team_id,
                                    'payment_date' => $paymentDate->toDateString(),
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);
                                // Continue with next member - don't let one failure stop the whole process
                                continue;
                            }
                        }
                    }
                }
            }
        }

        return $createdReminders;
    } catch (\Exception $e) {
        \Log::error('Failed to create payment reminders', [
            'schedule_id' => $this->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return empty array instead of failing completely
        return [];
        
        // Alternatively, if you want to propagate the error:
        // throw $e;
    }
}

    public function createReminders2()
    {
        // Only delete FUTURE reminders for this schedule
        PaymentReminder::where('payment_schedule_id', $this->id)
            ->where('reminder_date', '>=', now()->startOfDay())
            ->where('status', 'pending') // Only delete pending reminders
            ->delete();

        $frequency = $this->frequency ?? 'monthly';
        $startDate = Carbon::parse($this->duration_from);
        $endDate   = Carbon::parse($this->duration_to)->endOfDay();

        // Generate payment dates based on frequency
        $paymentDates = [];
        $currentDate  = $startDate->copy();

        if ($frequency === 'monthly') {
            while ($currentDate->lte($endDate)) {
                $paymentDay  = min($this->recurring_day, $currentDate->daysInMonth);
                $paymentDate = $currentDate->copy()->day($paymentDay);

                if ($paymentDate->gte($startDate) && $paymentDate->lte($endDate)) {
                    $paymentDates[] = $paymentDate->copy();
                }

                $currentDate->addMonth();
            }
        } else { // bi-weekly
            while ($currentDate->lte($endDate)) {
                if ($currentDate->gte($startDate)) {
                    $paymentDates[] = $currentDate->copy();
                }
                $currentDate->addWeeks(2);
            }
        }

        // Create reminder records for each payment date
        $reminderIntervals = $this->getReminderDaysByFrequency($frequency);
        $createdReminders  = [];

        foreach ($paymentDates as $paymentDate) {
            $periodKey = Payment::generatePeriodKey($this, $paymentDate->toDateString());

            foreach ($reminderIntervals as $reminderType => $daysBefore) {
                $reminderDate = $paymentDate->copy()->subDays($daysBefore);

                // Only create reminders for future dates
                if ($reminderDate->gte(now()->startOfDay())) {
                    // For individual user (if not a team payment)
                    if (!$this->team_id) {
                        $individualAmount = $this->amount ?? 0;
                        
                        // Default the expected charges to 0 for now
                        $expectedIndividualChargesPercentage = 0; //0%
                        $expectedIndividualCharges = ($individualAmount * $expectedIndividualChargesPercentage) / 100;
                    
                        $reminderData = [
                            'payment_schedule_id' => $this->id,
                            'user_id' => $this->user_id,
                            'payment_date' => $paymentDate->toDateString(),
                            'reminder_date' => $reminderDate->toDateString(),
                            'reminder_type' => $reminderType,
                            'period_key' => $periodKey,
                            'is_team_reminder' => false,
                            'amount' => $individualAmount,  // This should be the full amount
                            'charges' => $expectedIndividualCharges,  // This is the calculated charges
                            'status' => 'pending',
                            'payment_status' => 'pending',
                        ];
                    
                        $createdReminders[] = PaymentReminder::updateOrCreate(
                            [
                                'payment_schedule_id' => $this->id,
                                'user_id' => $this->user_id,
                                'payment_date' => $paymentDate->toDateString(),
                                'reminder_type' => $reminderType,
                            ],
                            $reminderData
                        );
                    }
                    // For team reminders if this is a team schedule
                    // For team reminders if this is a team schedule
                    if ($this->team_id) {
                        // Get all team members
                        $teamMembers = User::where('team_id', $this->team_id)->get();

                        // Include the team owner if they're not already in the members list
                        if (! $teamMembers->contains('id', $this->user_id)) {
                            $teamMembers->push($this->user);
                        }

                        foreach ($teamMembers as $member) {
                            // Get the team member record to find their percentage
                            $teamMember = TeamMember::where('team_id', $this->team_id)
                                ->where('user_id', $member->id)
                                ->first();

                            // Use the actual percentage from the team member
                            $percentage = $teamMember ? ($teamMember->percentage ?? 0) : 0;

                            // Calculate the member's portion of the payment
                            $totalAmount  = $this->amount ?? 0;
                            $memberAmount = ($totalAmount * $percentage) / 100;

                            // Default the expected charges to 0 for now
                            $expectedChargesPercentage = 0; //0%
                            $expectedCharges           = ($memberAmount * $expectedChargesPercentage) / 100;

                            $teamReminderData = [
                                'payment_schedule_id' => $this->id,
                                'user_id'             => $member->id,
                                'team_id'             => $this->team_id,
                                'payment_date'        => $paymentDate->toDateString(),
                                'reminder_date'       => $reminderDate->toDateString(),
                                'reminder_type'       => $reminderType,
                                'period_key'          => $periodKey,
                                'is_team_reminder'    => true,
                                'amount'              => $memberAmount,
                                'charges'             => $expectedCharges, // Set to 0 as requested
                                'status'              => 'pending',
                                'payment_status'      => 'pending',
                            ];

                            $createdReminders[] = PaymentReminder::updateOrCreate(
                                [
                                    'payment_schedule_id' => $this->id,
                                    'user_id'             => $member->id,
                                    'payment_date'        => $paymentDate->toDateString(),
                                    'reminder_type'       => $reminderType,
                                ],
                                $teamReminderData
                            );
                        }
                    }
                }
            }
        }

        return $createdReminders;
    }
    /**
     * Get the user that owns the payment schedule.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function billHistory()
    {
        return $this->belongsTo(BillHistory::class, 'bill_history_id');
    }

        /**
     * Get all reminders associated with this payment schedule.
     */
    public function reminders()
    {
        return $this->hasMany(PaymentReminder::class);
    }
    
    /**
     * Get pending reminders only.
     */
    public function pendingReminders()
    {
        return $this->hasMany(PaymentReminder::class)
            ->where('status', 'pending');
    }
    
    /**
     * Get upcoming reminders (pending and in the future).
     */
    public function upcomingReminders()
    {
        return $this->hasMany(PaymentReminder::class)
            ->where('status', 'pending')
            ->where('reminder_date', '>=', now()->startOfDay());
    }

}
