# Razorpay Payment Gateway Extension for Paymenter

A comprehensive payment gateway extension for Paymenter that supports both one-time payments and recurring subscriptions using Razorpay.

## Features

### One-Time Payments
- Secure payment processing with Razorpay
- Support for INR currency
- Automatic invoice status updates
- Webhook verification for payment confirmation

### Subscription Support
- **Recurring Billing**: Automatic monthly/yearly billing cycles
- **Subscription Plans**: Dynamic plan creation based on service configuration
- **Subscription Management**: Pause, resume, and cancel subscriptions
- **Automatic Billing**: Seamless recurring payments
- **Webhook Handling**: Real-time subscription status updates

## Installation

1. Place this extension in your Paymenter extensions directory: `extensions/Gateways/Razorpay-Paymenter/`
2. Enable the extension in your Paymenter admin panel
3. Configure your Razorpay API credentials

## Configuration

### Required Settings

- **Key ID**: Your Razorpay Live Mode API Key
- **Key Secret**: Your Razorpay Live Mode API Secret
- **Webhook Secret**: Your webhook verification secret

### Optional Settings

- **Test Mode**: Enable for testing with test credentials
- **Test Key ID**: Your Razorpay Test Mode API Key
- **Test Key Secret**: Your Razorpay Test Mode API Secret
- **Subscription Support**: Enable subscription functionality
- **Subscription Webhook Secret**: Separate webhook secret for subscription events

## Webhook Configuration

Configure your Razorpay webhook URL to:
```
https://your-domain.com/extensions/gateways/razorpay/webhook
```

### Supported Webhook Events

- `order.paid` - One-time payment success
- `subscription.activated` - Subscription activation
- `subscription.charged` - Recurring payment success
- `subscription.halted` - Subscription paused/suspended
- `subscription.cancelled` - Subscription cancellation
- `subscription.completed` - Subscription completion

## Subscription Features

### Automatic Plan Creation
The extension automatically creates Razorpay plans based on your service configuration:
- Billing period (monthly, yearly, etc.)
- Amount in INR
- Plan naming convention

### Subscription Management
Users can manage their subscriptions through dedicated interfaces:
- View all active subscriptions
- Pause/resume subscriptions
- Cancel subscriptions
- Update payment methods
- View billing history

### Recurring Billing
- Automatic invoice creation for recurring payments
- Seamless payment processing
- Service status synchronization

## Routes

### Payment Routes
- `POST /extensions/gateways/razorpay/webhook` - Webhook endpoint
- `POST /extensions/gateways/razorpay/callback/{invoiceId}` - Payment callback
- `GET /extensions/gateways/razorpay/cancel/{invoiceId}` - Payment cancellation

### Subscription Management Routes
- `GET /extensions/gateways/razorpay/subscriptions` - List all subscriptions
- `GET /extensions/gateways/razorpay/subscription/{subscriptionId}` - Subscription details
- `POST /extensions/gateways/razorpay/subscription/{subscriptionId}/cancel` - Cancel subscription
- `POST /extensions/gateways/razorpay/subscription/{subscriptionId}/pause` - Pause subscription
- `POST /extensions/gateways/razorpay/subscription/{subscriptionId}/resume` - Resume subscription

## Views

### Payment Views
- `pay.blade.php` - One-time payment checkout
- `subscription.blade.php` - Subscription setup
- `error.blade.php` - Error display

### Subscription Management Views
- `subscriptions.blade.php` - Subscription list
- `subscription-details.blade.php` - Individual subscription details

## Currency Support

Currently supports **INR (Indian Rupees)** only, as required by Razorpay.

## Testing

1. Enable Test Mode in the extension configuration
2. Use your Razorpay test API credentials
3. Test both one-time payments and subscriptions
4. Verify webhook handling with test events

## Security Features

- Webhook signature verification
- Separate webhook secrets for subscriptions
- Secure API key storage
- Input validation and sanitization

## Troubleshooting

### Common Issues

1. **Webhook Not Working**
   - Verify webhook URL is accessible
   - Check webhook secret configuration
   - Ensure proper SSL certificate

2. **Subscription Creation Fails**
   - Verify subscription support is enabled
   - Check service has recurring plan configuration
   - Validate Razorpay API credentials

3. **Currency Errors**
   - Ensure all products use INR currency
   - Check service plan configuration

### Logs

Check your application logs for detailed error messages:
- Razorpay API errors
- Webhook processing errors
- Subscription creation failures

## Support

For issues and questions:
1. Check the logs for error messages
2. Verify your Razorpay account configuration
3. Ensure webhook endpoints are accessible
4. Test with Razorpay's test mode first

## Changelog

### Version 2.0.0
- Added comprehensive subscription support
- Automatic plan creation
- Subscription management interfaces
- Enhanced webhook handling
- Improved error handling and logging

### Version 1.0.0
- Basic payment gateway functionality
- One-time payment support
- Webhook verification
- Basic error handling
