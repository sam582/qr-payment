<?php

namespace Sam\QrPayment\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Sam\QrPayment\Forms\QrPaymentMethodForm;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

if (! defined('QR_PAYMENT_METHOD_NAME')) {
    define('QR_PAYMENT_METHOD_NAME', 'qr_payment');
}

class QrPaymentServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        $this->setNamespace('plugins/qr-payment')
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes();

        $this->app->booted(function (): void {
            add_filter(PAYMENT_METHODS_SETTINGS_PAGE, function (string $html): string {
                return $html . QrPaymentMethodForm::create()->renderForm();
            }, 999);

            add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
                if ($class === PaymentMethodEnum::class) {
                    $values['QR_PAYMENT'] = QR_PAYMENT_METHOD_NAME;
                }
                return $values;
            }, 999, 2);

            add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
                if ($class === PaymentMethodEnum::class && $value == QR_PAYMENT_METHOD_NAME) {
                    $value = get_payment_setting('name', QR_PAYMENT_METHOD_NAME, 'UPI QR Payment');
                }
                return $value;
            }, 999, 2);

            add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, function (?string $html, array $data): ?string {
                if (! get_payment_setting('status', QR_PAYMENT_METHOD_NAME)) {
                    return $html;
                }

                $data = [
                    ...$data,
                    'paymentId'           => QR_PAYMENT_METHOD_NAME,
                    'paymentDisplayName'  => get_payment_setting('name', QR_PAYMENT_METHOD_NAME, 'UPI QR Payment'),
                    'supportedCurrencies' => get_all_currencies()->pluck('title')->toArray(),
                ];

                PaymentMethods::method(QR_PAYMENT_METHOD_NAME, [
                    'html' => view('plugins/qr-payment::payment-method', $data)->render(),
                ]);

                return $html;
            }, 999, 2);
        });

        add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, function (array $data, Request $request): array {
            if ($request->input('payment_method') === QR_PAYMENT_METHOD_NAME) {
                $data['error'] = false;
                $data['message'] = null;
                $token = class_exists(\Botble\Ecommerce\Facades\OrderHelper::class) 
                    ? \Botble\Ecommerce\Facades\OrderHelper::getOrderSessionToken()
                    : ($request->route('token') ?: $request->input('token'));

                $data['checkoutUrl'] = route('qr-payment.instructions', ['token' => $token]);
            }

            return $data;
        }, 999, 2);
    }
}
