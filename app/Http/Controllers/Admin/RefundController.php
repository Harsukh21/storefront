<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function store(Request $request, $orderNumber)
    {
        $order = DB::table('orders')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        $payment = DB::table('payments')
            ->where('order_id', $order->id)
            ->first();

        if (!$payment) {
            return back()->with('error', 'Payment not found for this order.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $payment->amount],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        // Check existing refunds
        $existingRefunds = DB::table('refunds')
            ->where('payment_id', $payment->id)
            ->where('status', '!=', 'cancelled')
            ->sum('amount');

        $remainingAmount = $payment->amount - $existingRefunds;

        if ($validated['amount'] > $remainingAmount) {
            return back()->withErrors([
                'amount' => "Maximum refundable amount is $" . number_format($remainingAmount, 2),
            ])->withInput();
        }

        // Create refund
        DB::table('refunds')->insert([
            'payment_id' => $payment->id,
            'amount' => $validated['amount'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'processed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update payment status if fully refunded
        $totalRefunded = $existingRefunds + $validated['amount'];
        if ($totalRefunded >= $payment->amount) {
            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => 'refunded',
                    'updated_at' => now(),
                ]);

            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'payment_status' => 'refunded',
                    'status' => 'refunded',
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => 'partially_refunded',
                    'updated_at' => now(),
                ]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Refund created successfully.',
        ]);
    }

    public function update(Request $request, $refundId)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processed,failed,cancelled'],
        ]);

        $refund = DB::table('refunds')->where('id', $refundId)->first();

        if (!$refund) {
            return back()->with('error', 'Refund not found.');
        }

        $updateData = [
            'status' => $validated['status'],
            'updated_at' => now(),
        ];

        if ($validated['status'] === 'processed' && !$refund->processed_at) {
            $updateData['processed_at'] = now();
        }

        DB::table('refunds')
            ->where('id', $refundId)
            ->update($updateData);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Refund status updated successfully.',
        ]);
    }
}
