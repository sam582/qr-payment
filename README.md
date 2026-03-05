# Botble UPI QR Payment Plugin

UPI QR Code Payment Gateway integration for Botble eCommerce.
This plugin allows your customers to place an order and pay via any UPI app (PhonePe, Google Pay, Paytm) by scanning a static QR code uploaded by the Admin.

## Features
- Add "UPI QR Payment" to the checkout page.
- Beautiful UI instructions post-checkout.
- Admin dashboard settings to configure UPI ID, Merchant name, and QR Code image.
- Status seamlessly transitions to `Payment Verification Pending`.
- **0% Transaction Fees**: Accept payments with absolutely zero processing or transaction charges.

## Installation Guide

1. **Upload Plugin**: Place the entire `qr-payment` folder into your Botble installation's `platform/plugins/` directory:
   `/platform/plugins/qr-payment/`

2. **Activate Plugin**: 
   - Login to your Botble Admin Panel.
   - Navigate to **Plugins** in the sidebar.
   - Find **UPI QR Payment** and click the **Activate** button.

## Admin Configuration Guide

1. Navigate to **Admin -> Payments**.
2. Scroll down until you see **UPI QR Payment** in the list of payment gateways.
3. Click on the item to expand the settings.
4. Fill in the required fields:
   - **Method Name**: Typically "UPI QR Payment".
   - **UPI ID**: Your business UPI ID (e.g., `merchchant@upi`).
   - **Merchant Name**: The name displayed when users scan the code.
   - **QR Code Image**: Upload the static QR code image you received from your bank or UPI provider.
   - **Payment Instructions**: Leave default or explicitly tell users how to pay.
5. Set Status to **Active**.
6. Click **Save**.

## Example UI / Order Flow

1. **Checkout**: 
   - Customers will see a new payment method called "UPI QR Payment" at checkout.
   - Selecting this method will inform them that instructions to scan the QR will follow.
2. **Post-Checkout**: 
   - After clicking "Place Order", the user is redirected to the `/payment/qr-payment/instructions/{token}` page.
   - They will see the Total Amount, the uploaded **QR Code**, Your **UPI ID**, and **Merchant Name**.
3. **Payment Confirmation**: 
   - Once they pay, they click "I have paid (Payment Done)".
   - The order `status` remains pending and an order history log is added reading: `Payment verification pending.`. 
   - Admin goes to **Admin -> Orders**, manually verifies if the money arrived in the bank, and updates the order status to `Completed` or `Canceled` accordingly.

## Compatibility
Fully compatible with Botble CMS v6.x, v7.x and the Botble eCommerce Plugin.
