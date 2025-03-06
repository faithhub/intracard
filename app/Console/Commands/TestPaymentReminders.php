<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestPaymentReminders extends Command
{
    protected $signature = 'reminders:test {type=both : The type of reminders to test (individual, team, or both)}';
    protected $description = 'Test payment reminder notifications for individual users and teams';

    public function handle()
    {
        $type = $this->argument('type');
        
        if ($type === 'individual' || $type === 'both') {
            $this->testIndividualReminders();
        }
        
        if ($type === 'team' || $type === 'both') {
            $this->testTeamReminders();
        }
        
        $this->info('Test completed.');
        
        return 0;
    }
    
    private function testIndividualReminders()
    {
        $this->info('Testing individual payment reminders...');
        
        // Get or create a test user
        $user = User::firstOrCreate(
            ['email' => 'test.individual@example.com'],
            [
                'name' => 'Test Individual User',
                'password' => bcrypt('password'),
            ]
        );
        
        // Create a test payment schedule with today's reminders
        $schedule = $this->createTestSchedule($user->id, false);
        
        // Run the payment processor
        $this->info('Running payment processor for individual test...');
        $this->call('payments:process');
        
        $this->info('Individual test complete. Check logs for results.');
    }
    
    private function testTeamReminders()
    {
        $this->info('Testing team payment reminders...');
        
        // Get or create a test team owner
        $teamOwner = User::firstOrCreate(
            ['email' => 'test.teamowner@example.com'],
            [
                'name' => 'Test Team Owner',
                'password' => bcrypt('password'),
            ]
        );
        
        // Get or create a test team
        $team = Team::firstOrCreate(
            ['name' => 'Test Team'],
            [
                'user_id' => $teamOwner->id,
                'description' => 'Test team for payment reminders',
            ]
        );
        
        // Create some team members if needed
        $this->createTeamMembers($team);
        
        // Create a test team payment schedule with today's reminders
        $schedule = $this->createTestSchedule($teamOwner->id, true, $team->id);
        
        // Run the payment processor
        $this->info('Running payment processor for team test...');
        $this->call('payments:process');
        
        $this->info('Team test complete. Check logs for results.');
    }
    
    private function createTestSchedule($userId, $isTeamPayment, $teamId = null)
    {
        // Today and future dates
        $today = Carbon::now()->startOfDay();
        $startDate = $today->copy()->subMonth();
        $endDate = $today->copy()->addYear();
        
        // Create or update a test payment schedule
        $schedule = PaymentSchedule::updateOrCreate(
            [
                'user_id' => $userId,
                'is_team_payment' => $isTeamPayment,
                'team_id' => $teamId,
            ],
            [
                'payment_type' => 'rent',
                'amount' => 1000.00,
                'frequency' => 'monthly',
                'recurring_day' => $today->day,
                'status' => 'active',
                'duration_from' => $startDate,
                'duration_to' => $endDate,
                'bill_id' => 1, // You'll need a valid bill ID here
                // Set reminder dates to trigger for today
                'reminder_dates' => json_encode([
                    $today->copy()->addDays(7)->format('Y-m-d') => [
                        '7_days_before' => $today->format('Y-m-d'),
                    ],
                    $today->copy()->addDays(5)->format('Y-m-d') => [
                        '5_days_before' => $today->format('Y-m-d'),
                    ],
                    $today->copy()->addDays(2)->format('Y-m-d') => [
                        '2_days_before' => $today->format('Y-m-d'),
                    ],
                ]),
            ]
        );
        
        $this->info("Created test " . ($isTeamPayment ? "team" : "individual") . " payment schedule ID: {$schedule->id}");
        return $schedule;
    }
    
    private function createTeamMembers($team)
    {
        // Create 3 test team members
        for ($i = 1; $i <= 3; $i++) {
            $email = "test.member{$i}@example.com";
            
            // Check if this email already exists in team_members
            if (TeamMember::where('team_id', $team->id)->where('email', $email)->exists()) {
                $this->info("Team member with email {$email} already exists");
                continue;
            }
            
            $member = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => "Test Team Member {$i}",
                    'last_name' => "Test Team Member {$i}",
                    'password' => bcrypt('password'),
                ]
            );
            
            // Create the team member
            $team->teamMembers()->updateOrCreate(
                ['email' => $member->email], // The unique identifier
                [
                    'user_id' => $member->id,
                    'name' => $member->name,
                    'status' => 'accepted',
                    'role' => 'member',
                    'percentage' => 25,
                    'amount' => 0,
                ]
            );
            
            $this->info("Added member {$member->name} to team");
        }
    }
}