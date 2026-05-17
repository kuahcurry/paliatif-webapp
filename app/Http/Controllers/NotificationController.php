<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = UserNotification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(UserNotification $userNotification): RedirectResponse
    {
        if ($userNotification->user_id !== request()->user()->id) {
            abort(403);
        }

        $userNotification->markAsRead();

        if ($userNotification->url && str_starts_with($userNotification->url, '/')) {
            return redirect()->to($userNotification->url);
        }

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        UserNotification::where('user_id', $request->user()->id)
            ->unread()
            ->update(['is_read' => true]);

        return back();
    }
}
