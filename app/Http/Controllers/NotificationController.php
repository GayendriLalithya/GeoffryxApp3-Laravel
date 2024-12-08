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


    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->status = 'read';
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
}

