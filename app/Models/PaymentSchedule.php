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
        'payment_type',
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

    public function generateRecurringDates2()
    {
        $dates = [];
        $start = Carbon::parse($this->duration_from);
        $end   = Carbon::parse($this->duration_to);

        // Loop through each month between duration_from and duration_to
        while ($start->lte($end)) {
            $recurringDate = $start->copy()->day($this->recurring_day);

            // Only add valid dates
            if ($recurringDate->isValid() && $recurringDate->lte($end)) {
                $dates[] = $recurringDate->toDateString();
            }

            // Move to the next month
            $start->addMonth();
        }

        return $dates;
    }

    public function generateRecurringDates4()
    {
        $dates        = [];
        $startDate    = Carbon::parse($this->duration_from);
        $endDate      = Carbon::parse($this->duration_to);
        $recurringDay = $this->recurring_day;
        $frequency    = $this->frequency ?? 'monthly'; // Default to 'monthly'

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($frequency === 'monthly') {
                $recurringDate = $currentDate->copy()->day($recurringDay);

                if ($recurringDate->isValid() && $recurringDate->lte($endDate)) {
                    $dates[] = $recurringDate->toDateString();
                }

                $currentDate->addMonth();
            } elseif ($frequency === 'bi-weekly') {
                $dates[] = $currentDate->toDateString();
                $currentDate->addWeeks(2);
            }
        }

        return $dates;
    }

    public function generateRecurringDates1()
    {
        $dates        = [];
        $startDate    = Carbon::parse($this->duration_from);
        $endDate      = Carbon::parse($this->duration_to);
        $recurringDay = $this->recurring_day;
        $frequency    = $this->frequency ?? 'monthly'; // Default to 'monthly'

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($frequency === 'monthly') {
                // Adjust for the recurring day of the current month
                $recurringDate = $currentDate->copy()->day($recurringDay);

                // Validate and add only if it's within the range
                if ($recurringDate->isValid() && $recurringDate->lte($endDate)) {
                    $dates[] = $recurringDate->toDateString();
                }

                // Move to the next month
                $currentDate->addMonth()->startOfMonth();
            } elseif ($frequency === 'bi-weekly') {
                // Add the current date and move 2 weeks forward
                $dates[] = $currentDate->toDateString();
                $currentDate->addWeeks(2);
            }
        }

        return $dates;
    }

    public function generateRecurringDates()
    {
        $dates = [];
        $startDate = Carbon::parse($this->duration_from);
        $endDate = Carbon::parse($this->duration_to);
        $recurringDay = $this->recurring_day;
        $frequency = $this->frequency ?? 'monthly';
    
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
                    !in_array($dateString, $addedDates)) {
                    
                    $dates[] = $dateString;
                    $addedDates[] = $dateString;
                }
    
                // Move to the next month
                $currentDate->addMonth()->startOfMonth();
            } elseif ($frequency === 'bi-weekly') {
                $dateString = $currentDate->toDateString();
                
                if (!in_array($dateString, $addedDates)) {
                    $dates[] = $dateString;
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

    public function generateReminders_oldd()
    {
        $reminders   = [];
        $frequency   = $this->frequency ?? 'monthly';
        $startDate   = $this->duration_from;
        $endDate     = $this->duration_to->endOfDay();
        $currentDate = Carbon::now();

        // Your existing reminder date generation code...
        $nextPaymentDate = $startDate->copy()->day($this->recurring_day);
        if ($nextPaymentDate->lt($currentDate)) {
            $nextPaymentDate = $currentDate->copy()->startOfMonth()->day($this->recurring_day);
        }

        if (! $nextPaymentDate->isValid()) {
            $nextPaymentDate = $nextPaymentDate->startOfMonth()->addMonth()->day($this->recurring_day);
        }

        $defaultReminderDays = $this->getReminderDaysByFrequency($frequency);
        $maxIterations       = $frequency === 'bi-weekly' ? 26 : 12;

        while ($nextPaymentDate->lte($endDate) && $maxIterations > 0) {
            if ($nextPaymentDate->isValid()) {
                $paymentReminders = [];
                foreach ($defaultReminderDays as $reminderKey => $daysBefore) {
                    $reminderDate = $nextPaymentDate->copy()->subDays($daysBefore);
                    if ($reminderDate->gte($startDate)) {
                        $paymentReminders[$reminderKey] = $reminderDate->toDateString();

                        // Add this part: Queue the reminder notifications
                        if (Carbon::parse($reminderDate)->isFuture()) {
                            if ($this->team_id) {
                                // Queue team member reminder
                                TeamMemberPaymentReminderJob::dispatch(
                                    $this->user,
                                    $this,
                                    $reminderKey,
                                    Carbon::parse($reminderDate)
                                )->delay(Carbon::parse($reminderDate));
                            } else {
                                // Queue regular payment reminder
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

                $reminders[$nextPaymentDate->toDateString()] = $paymentReminders;
            }

            if ($frequency === 'bi-weekly') {
                $nextPaymentDate->addWeeks(2);
            } elseif ($frequency === 'monthly') {
                $nextPaymentDate->addMonth();
            }

            $maxIterations--;
        }

        return $reminders;
    }

    public function generateReminders()
    {
        $reminders = [];
        $frequency = $this->frequency ?? 'monthly';
        $startDate = $this->duration_from;
        $endDate = $this->duration_to->endOfDay();
        $currentDate = Carbon::now();
    
        // Initialize next payment date based on frequency
        if ($frequency === 'monthly') {
            $nextPaymentDate = $startDate->copy()->day($this->recurring_day);
            if (!$nextPaymentDate->isValid()) {
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
        $maxIterations = $frequency === 'bi-weekly' ? 26 : 12;
    
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
                if (!empty($paymentReminders)) {
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
    /**
     * Get the user that owns the payment schedule.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function billHistory()
    {
        return $this->belongsTo(BillHistory::class, 'bill_history_id');
    }

}
