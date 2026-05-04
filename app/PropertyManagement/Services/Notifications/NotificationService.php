<?php

namespace App\PropertyManagement\Services\Notifications;

use App\PropertyManagement\Models\Notification;
use App\PropertyManagement\Models\RentPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Create payment due notification (30 days before due date)
     */
    public function createPaymentDueNotifications(): void
    {
        $today = Carbon::now()->toDateString();
        $thirtyDaysFromNow = Carbon::now()->addDays(30)->toDateString();

        // Get all unpaid payments that are due in exactly 30 days (or within 30 days range)
        $payments = RentPayment::with(['contract.client', 'contract.unit.building'])
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', '>=', $today)
            ->whereDate('due_date', '<=', $thirtyDaysFromNow)
            ->get();

        foreach ($payments as $payment) {
            $daysUntilDue = Carbon::now()->diffInDays($payment->due_date);
            $dueDateFormatted = Carbon::parse($payment->due_date)->format('Y-m-d');

            // Only create notification if payment is due in 30 days or less
            if ($daysUntilDue <= 30) {
                // Check if notification already exists for this payment
                $existingNotification = Notification::where('rent_payment_id', $payment->id)
                    ->where('type', 'payment_due')
                    ->first();

                if (!$existingNotification) {
                    Notification::create([
                        'user_id' => null, // Can be set to specific user later
                        'type' => 'payment_due',
                        'title' => 'استحقاق دفعة قادم',
                        'message' => "رقم المكتب: {$payment->contract->unit->unit_number} - اسم المبنى: {$payment->contract->unit->building->name} - القيمة المستحقة: " . number_format($payment->total_value, 2) . " ريال - تاريخ الاستحقاق: {$dueDateFormatted}",
                        'rent_payment_id' => $payment->id,
                        'contract_id' => $payment->contract_id,
                        'is_read' => false,
                    ]);
                }
            }
        }
    }

    /**
     * Get unread notifications for all users (or specific user)
     */
    public function getUnreadNotifications(?int $userId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Notification::where('is_read', false)
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get();
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(?int $userId = null): int
    {
        $query = Notification::where('is_read', false);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(?int $userId = null): int
    {
        $query = Notification::where('is_read', false);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Delete notification
     */
    public function deleteNotification(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);

        if ($notification) {
            $notification->delete();
            return true;
        }

        return false;
    }
}

