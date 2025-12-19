<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('payments')
            ->leftJoin('orders', 'payments.order_id', '=', 'orders.id')
            ->select(
                'payments.*',
                'orders.order_number',
                'orders.grand_total as order_total'
            )
            ->orderByDesc('payments.created_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('payments.transaction_id', 'ILIKE', "%{$search}%")
                    ->orWhere('orders.order_number', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('payments.status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->where('payments.provider', $request->provider);
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'filters' => [
                'search' => $request->query('search'),
                'status' => $request->query('status'),
                'provider' => $request->query('provider'),
            ],
        ]);
    }

    public function show(int $payment)
    {
        $paymentRecord = DB::table('payments')
            ->leftJoin('orders', 'payments.order_id', '=', 'orders.id')
            ->select(
                'payments.*',
                'orders.order_number',
                'orders.grand_total as order_total',
                'orders.status as order_status'
            )
            ->where('payments.id', $payment)
            ->first();

        if (!$paymentRecord) {
            abort(404);
        }

        // Get refunds for this payment
        $refunds = DB::table('refunds')
            ->where('payment_id', $payment)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.payments.show', [
            'payment' => $paymentRecord,
            'refunds' => $refunds,
        ]);
    }
}


