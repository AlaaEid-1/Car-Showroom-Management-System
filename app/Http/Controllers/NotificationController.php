<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a list of the user's notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20)->withQueryString();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function read(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // Redirect to the target URL if available
        $url = $notification->data['url'] ?? null;
        if ($url) {
            return redirect($url);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function readAll()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
