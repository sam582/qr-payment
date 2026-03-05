<?php

namespace Sam\QrPayment\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Order;
use Botble\Payment\Models\Payment;
use Illuminate\Http\Request;

class QrPaymentController extends BaseController
{

    public function instructions(string $token, Request $request)
    {
        $orders = Order::query()->where('token', $token)->get();
        if ($orders->isEmpty()) {
            abort(404);
        }
        
        $totalAmount = $orders->sum('amount');
        
        return view('plugins/qr-payment::instructions', compact('orders', 'totalAmount', 'token'));
    }

    public function confirm(string $token, Request $request, BaseHttpResponse $response)
    {
        $orders = Order::query()->where('token', $token)->get();
        if ($orders->isEmpty()) {
            abort(404);
        }
        
        $orderFirst = $orders->first();

        $payment = Payment::query()
            ->where('order_id', $orderFirst->id)
            ->where('payment_channel', QR_PAYMENT_METHOD_NAME)
            ->first();

        if (! $payment) {
            do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                'amount'          => $orders->sum('amount'),
                'currency'        => $orderFirst->currency->title ?? 'USD',
                'charge_id'       => uniqid('QR-'),
                'payment_channel' => QR_PAYMENT_METHOD_NAME,
                'status'          => \Botble\Payment\Enums\PaymentStatusEnum::PENDING,
                'customer_id'     => $orderFirst->user_id,
                'payment_type'    => 'confirm',
                'order_id'        => $orderFirst->id,
            ], $request);
        }
        
        foreach ($orders as $order) {
            \Botble\Ecommerce\Models\OrderHistory::query()->create([
                'action'      => 'payment_verification_pending',
                'description' => 'Customer clicked Payment Done. Verification pending.',
                'order_id'    => $order->id,
                'user_id'     => 0,
            ]);
        }
        
        return $response
            ->setNextUrl(route('public.checkout.success', $token))
            ->setMessage(trans('plugins/qr-payment::qr-payment.payment_verification_pending'));
    }
}
