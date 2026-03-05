<?php

namespace Sam\QrPayment\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Payment\Forms\PaymentMethodForm;

class QrPaymentMethodForm extends PaymentMethodForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(QR_PAYMENT_METHOD_NAME)
            ->paymentName('UPI QR Payment')
            ->paymentDescription('Allow customers to pay via UPI by scanning your QR Code with 0% transaction fees.')
            ->paymentLogo(url('vendor/core/plugins/qr-payment/images/upi.svg'))
            ->paymentUrl('https://npci.org.in')
            ->paymentInstructions('Your customers will see the QR code on the next page after placing the order.')
            ->add(
                get_payment_setting_key('upi_id', QR_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label('UPI ID')
                    ->value(get_payment_setting('upi_id', QR_PAYMENT_METHOD_NAME))
            )
            ->add(
                get_payment_setting_key('merchant_name', QR_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label('Merchant Name')
                    ->value(get_payment_setting('merchant_name', QR_PAYMENT_METHOD_NAME))
            )
            ->add(
                get_payment_setting_key('qr_image', QR_PAYMENT_METHOD_NAME),
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label('QR Code Image')
                    ->value(get_payment_setting('qr_image', QR_PAYMENT_METHOD_NAME))
                    ->helperText('Upload your static UPI QR code image here.')
            )
            ->add(
                get_payment_setting_key('instructions', QR_PAYMENT_METHOD_NAME),
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label('Payment Instructions')
                    ->value(get_payment_setting('instructions', QR_PAYMENT_METHOD_NAME, 'Scan the QR code using any UPI app (PhonePe, Google Pay, Paytm). Pay the exact order amount. After payment click "I have paid".'))
                    ->rows(3)
            );
    }
}
