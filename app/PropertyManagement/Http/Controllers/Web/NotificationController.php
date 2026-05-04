<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function index()
    {
        $notifications = $this->notificationService->getUnreadNotifications();
        $unreadCount = $this->notificationService->getUnreadCount();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id);
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $count = $this->notificationService->markAllAsRead();
        
        return response()->json(['success' => true, 'count' => $count]);
    }

    public function destroy($id)
    {
        $deleted = $this->notificationService->deleteNotification($id);
        
        if ($deleted) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
}

