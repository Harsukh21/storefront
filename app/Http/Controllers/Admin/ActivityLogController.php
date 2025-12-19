<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('admin_activity_logs')
            ->leftJoin('admins', 'admin_activity_logs.admin_id', '=', 'admins.id')
            ->select(
                'admin_activity_logs.*',
                'admins.name as admin_name',
                'admins.email as admin_email'
            )
            ->orderByDesc('admin_activity_logs.created_at');

        if ($request->filled('admin_id')) {
            $query->where('admin_activity_logs.admin_id', $request->admin_id);
        }

        if ($request->filled('action')) {
            $query->where('admin_activity_logs.action', 'ILIKE', "%{$request->action}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('admin_activity_logs.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('admin_activity_logs.created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->withQueryString();

        // Get all admins for filter
        $admins = DB::table('admins')->select('id', 'name', 'email')->orderBy('name')->get();

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'admins' => $admins,
            'filters' => [
                'admin_id' => $request->query('admin_id'),
                'action' => $request->query('action'),
                'date_from' => $request->query('date_from'),
                'date_to' => $request->query('date_to'),
            ],
        ]);
    }
}


