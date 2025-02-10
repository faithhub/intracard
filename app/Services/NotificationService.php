<?php
namespace App\Services;

use App\Events\NotificationEvent;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Create a new notification and broadcast it
     *
     * @param string $title
     * @param string $message
     * @param string $category
     * @param array $data
     * @return Notification
     */
    public function create(string $title, string $message, string $category = 'general', array $data = [], $userId = null)
    {
        $notification = Notification::create([
            'user_id'     => $userId ?? Auth::id(),
            'title'       => $title,
            'message'     => $message,
            'category'    => $category,
            'data'        => $data,
            'is_read'     => false,
            'is_archived' => false,
        ]);

        // Broadcast the notification event
        broadcast(new NotificationEvent($notification->title, $notification->message))->toOthers();

        return $notification;
    }

    /**
     * Create a card-related notification with broadcast
     *
     * @param string $type
     * @param array $data
     * @return Notification
     */
    public function createCardNotification(string $type, array $data)
    {
        $title   = $this->getCardNotificationTitle($type);
        $message = $this->getCardNotificationMessage($type, $data);

        return $this->create(
            $title,
            $message,
            'payment',
            $data
        );
    }

    /**
     * Get notification title based on type
     *
     * @param string $type
     * @return string
     */
    private function getCardNotificationTitle(string $type): string
    {
        return match ($type) {
            'card_added' => 'New Card Added',
            'card_deleted' => 'Card Deleted',
            'card_primary' => 'Primary Card Updated',
            'card_payment_success' => 'Payment Processed Successfully', // New notification type
            'card_payment_failed' => 'Payment Processing Failed',       // New notification type
            default => 'Card Update',
        };
    }

    /**
     * Get notification message based on type and data
     *
     * @param string $type
     * @param array $data
     * @return string
     */
    private function getCardNotificationMessage(string $type, array $data): string
    {
        $last4      = $data['last4'] ?? '****';
        $amount     = number_format($data['amount'] ?? 0, 2);
        $paymentFor = $data['payment_for'] ?? 'payment'; // New data field

        return match ($type) {
            'card_added' => "A new card ending in {$last4} has been added to your account.",
            'card_deleted' => "The card ending in {$last4} has been removed from your account.",
            'card_primary' => "Card ending in {$last4} has been set as your primary card.",
            'card_payment_success' => "Payment of \${$amount} processed successfully for {$paymentFor} using card ending in {$last4}.", // New notification type message
            'card_payment_failed' => "Payment of \${$amount} failed for {$paymentFor} using card ending in {$last4}.",                  // New notification type message
            default => "Your card information has been updated.",
        };
    }

    public function createWalletNotification(string $type, array $data)
    {
        $title   = $this->getWalletNotificationTitle($type);
        $message = $this->getWalletNotificationMessage($type, $data);

        return $this->create(
            $title,
            $message,
            'payment', // Using payment category instead of wallet
            $data
        );
    }

/**
 * Get wallet notification title based on type
 *
 * @param string $type
 * @return string
 */
    private function getWalletNotificationTitle(string $type): string
    {
        return match ($type) {
            'wallet_funded' => 'Wallet Funded Successfully',
            'fund_failed' => 'Wallet Funding Failed',
            'wallet_payment_success' => 'Payment Processed Successfully', // New notification type
            'wallet_payment_failed' => 'Payment Failed',                  // New notification type
            default => 'Wallet Transaction Update',
        };
    }

/**
 * Get wallet notification message based on type and data
 *
 * @param string $type
 * @param array $data
 * @return string
 */
    private function getWalletNotificationMessage(string $type, array $data): string
    {
        $amount     = number_format($data['amount'] ?? 0, 2);
        $service    = $data['service'] ?? 'service';
        $cardLast4  = $data['card_last4'] ?? '****';
        $paymentFor = $data['payment_for'] ?? 'payment'; // New data field

        return match ($type) {
            'wallet_funded' => "Successfully funded wallet with \${$amount} for {$service} using card ending in {$cardLast4}.",
            'fund_failed' => "Failed to fund wallet with \${$amount} for {$service} using card ending in {$cardLast4}.",
            'wallet_payment_success' => "Payment of \${$amount} processed successfully for {$paymentFor}.", // New notification type message
            'wallet_payment_failed' => "Payment of \${$amount} failed for {$paymentFor}.",                  // New notification type message
            default => "Your wallet transaction has been updated.",
        };
    }

    /**
     * Get unread notifications count
     *
     * @return int
     */
    public function getUnreadCount(): int
    {
        return Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread notifications count by category
     *
     * @return array
     */
    public function getUnreadCountByCategory(): array
    {
        $categories = ['general', 'payment', 'reminder'];
        $counts     = [];

        foreach ($categories as $category) {
            $counts[$category] = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->where('category', $category)
                ->count();
        }

        return $counts;
    }

    /**
     * Get paginated notifications for a user
     *
     * @param string|null $category
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedNotifications(?string $category = null, int $perPage = 10)
    {
        $query = Notification::where('user_id', Auth::id())
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        return $query->paginate($perPage);
    }
}
