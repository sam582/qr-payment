@extends('plugins/ecommerce::orders.master')
@section('title', 'QR Payment Instructions')
@section('content')
<div class="container my-5" style="padding: 60px 0; min-height: 50vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card shadow-sm" style="border: 1px solid #eaeaea; border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white text-center py-4" style="border-bottom: 1px solid #eaeaea;">
                    <h4 class="mb-0" style="font-weight: 600; color: #333;">Pay via UPI QR Code</h4>
                </div>
                <div class="card-body text-center p-5">
                    
                    <h2 class="text-primary mb-4" style="font-weight: 700; color: #0d6efd !important;">
                        {{ format_price($totalAmount) }}
                    </h2>

                    @if (get_payment_setting('qr_image', QR_PAYMENT_METHOD_NAME))
                        <div class="mb-4 d-flex justify-content-center">
                            <div class="p-3" style="border: 1px solid #eee; border-radius: 8px; display: inline-block;">
                                <img src="{{ \Botble\Media\Facades\RvMedia::getImageUrl(get_payment_setting('qr_image', QR_PAYMENT_METHOD_NAME)) }}" alt="QR Code" class="img-fluid" style="max-width: 250px; display: block; margin: 0 auto;">
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-4">
                            QR Code image has not been configured by the administrator. Please contact support.
                        </div>
                    @endif

                    <div class="mb-4">
                        <p class="mb-1" style="color: #666; font-size: 14px;">UPI ID</p>
                        <p class="lead" style="font-weight: 600; font-size: 18px;">{{ get_payment_setting('upi_id', QR_PAYMENT_METHOD_NAME, 'Not Set') }}</p>

                        <p class="mb-1" style="color: #666; font-size: 14px;">Merchant Name</p>
                        <p class="" style="font-weight: 600; font-size: 18px;">{{ get_payment_setting('merchant_name', QR_PAYMENT_METHOD_NAME, 'Not Set') }}</p>
                    </div>

                    <div class="p-4 rounded text-start mb-4" style="background-color: #f8f9fa;">
                        <h5 style="font-size: 16px; font-weight: 600;">Instructions</h5>
                        <p class="mb-0" style="color: #555; font-size: 15px;">{!! nl2br(e(get_payment_setting('instructions', QR_PAYMENT_METHOD_NAME, 'Scan the QR code using any UPI app. Pay the exact order amount. After payment click "I have paid".'))) !!}</p>
                    </div>

                    <form action="{{ route('qr-payment.confirm', ['token' => $token]) }}" method="POST">
                        @csrf
                        <div class="d-grid gap-3" style="display: grid; gap: 1rem;">
                            <button type="submit" class="btn btn-primary" style="padding: 12px 20px; font-weight: 600; font-size: 16px; width: 100%;">I have paid (Payment Done)</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
