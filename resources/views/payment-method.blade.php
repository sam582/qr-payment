<x-plugins-payment::payment-method
    :$selecting
    :name="$paymentId"
    :paymentName="$paymentDisplayName"
>
    <div class="checkout-payment-instructions my-3 p-3 border rounded bg-light">
        <p class="mb-2"><strong><i class="fa fa-info-circle"></i> Instructions:</strong></p>
        <p class="mb-0 text-muted">{!! BaseHelper::clean(nl2br(get_payment_setting('instructions', QR_PAYMENT_METHOD_NAME, 'Scan the QR code using any UPI app. You will be shown the QR code to scan on the next step.'))) !!}</p>
        <p class="mt-3 text-info"><small>You will be shown the QR code to scan on the next step after clicking <strong>Place Order</strong>.</small></p>
    </div>
</x-plugins-payment::payment-method>
