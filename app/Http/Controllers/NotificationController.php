<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function showNotifications(Request $request)
    {
        $userId = auth()->id();
        $status = $request->filter ?? 'all';
    
        if ($status === 'unread') {
            $notifications = Notification::where('user_id', $userId)
                                          ->where('status', 'unread')
                                          ->orderBy('created_at', 'desc')
                                          ->get();
        } else {
            $notifications = Notification::where('user_id', $userId)
                                          ->orderBy('created_at', 'desc')
                                          ->get();
        }
    
        return view('notifications', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,notification_id',
        ]);

        $notification = Notification::find($request->notification_id);

        if ($notification) {
            $notification->status = 'read';
            $notification->save();
            return response()->json(['success' => true]);
        } else {
            // Ensure this doesn't return an error unless necessary
            return response()->json(['success' => false, 'message' => 'Notification not found']);
        }
    }

    public function dashboard()
    {
        // Get the logged-in user's ID
        $userId = auth()->id();
    
        // Count unread notifications for the logged-in user
        $unreadNotificationsCount = Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();
        
        // Pass the unread count to the view
        return view('components.topbar', compact('unreadNotificationsCount'));
    }


    public function getUnreadCount()
    {
        $userId = auth()->id();

        // Count unread notifications for the logged-in user
        $unreadCount = Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();

        return response()->json(['unreadCount' => $unreadCount]);
    }
}

