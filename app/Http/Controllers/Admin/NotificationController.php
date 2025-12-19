<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $adminId = auth('admin')->id();

        $query = DB::table('admin_notifications')
            ->where('admin_id', $adminId)
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->boolean('unread_only')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        // Get unread count
        $unreadCount = DB::table('admin_notifications')
            ->where('admin_id', $adminId)
            ->whereNull('read_at')
            ->count();

        return view('admin.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'filters' => [
                'type' => $request->query('type'),
                'unread_only' => $request->boolean('unread_only'),
            ],
        ]);
    }

    public function markAsRead(int $notification)
    {
        $notificationRecord = DB::table('admin_notifications')
            ->where('id', $notification)
            ->where('admin_id', auth('admin')->id())
            ->first();

        if (!$notificationRecord) {
            abort(404);
        }

        if (!$notificationRecord->read_at) {
            DB::table('admin_notifications')
                ->where('id', $notification)
                ->update(['read_at' => now()]);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        DB::table('admin_notifications')
            ->where('admin_id', auth('admin')->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'All notifications marked as read.']);

        return redirect()->back();
    }

    public function destroy(int $notification)
    {
        $notificationRecord = DB::table('admin_notifications')
            ->where('id', $notification)
            ->where('admin_id', auth('admin')->id())
            ->first();

        if (!$notificationRecord) {
            abort(404);
        }

        DB::table('admin_notifications')->where('id', $notification)->delete();

        session()->flash('toast', ['type' => 'success', 'message' => 'Notification deleted.']);

        return redirect()->back();
    }
}


