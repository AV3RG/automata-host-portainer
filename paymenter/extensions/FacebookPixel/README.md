# Facebook Pixel Extension for Paymenter

This extension provides comprehensive Facebook Pixel tracking and Conversions API integration for your Paymenter application. It automatically tracks purchase events, subscription events, and allows for custom event tracking.

## Features

- **Conversions API**: Server-side event tracking for better accuracy
- **Automatic Event Tracking**: 
  - Purchase events (invoice payments)
  - Subscription events (created, renewed, cancelled)
- **Custom Event Tracking**: Send custom events via API
- **Test Events**: Send test events to verify API integration
- **Debug Mode**: Test events and monitor API responses
- **Privacy Compliant**: Hashes user data according to Facebook requirements

## Installation

1. **Enable the Extension**: Go to your Paymenter admin panel and enable the Facebook Pixel extension
2. **Configure Settings**: Configure your Facebook Pixel ID, Access Token, and other settings through the extension configuration panel
3. **Include Pixel Code**: Add the generated pixel code to your theme's head section

## Configuration

### Required Settings

- **Facebook Pixel ID**: Your unique pixel identifier from Facebook Ads Manager
- **Facebook Access Token**: App access token for Conversions API

### Optional Settings

- **Enable Conversions API**: Toggle server-side event tracking
- **Track Purchase Events**: Automatically track invoice payments
- **Track Subscription Events**: Track subscription lifecycle events
- **Track Registration Events**: Track user registration and verification
- **Debug Mode**: Enable detailed logging for testing
- **Test Event Code**: Optional test event code for development

## Usage

### Automatic Event Tracking

The extension automatically tracks these events:

- **Purchase**: When an invoice is paid
- **Subscribe**: When a subscription is created or renewed
- **Unsubscribe**: When a subscription is cancelled
- **CompleteRegistration**: When a user registers or verifies their account

### Custom Event Tracking

You can track custom events programmatically:

```php
use Paymenter\Extensions\Others\FacebookPixel\FacebookPixelService;

$service = app(FacebookPixelService::class);

// Track a custom event
$service->trackCustomEvent('AddToCart', $userData, [
    'currency' => 'USD',
    'value' => 29.99,
    'content_ids' => ['product_123'],
    'content_type' => 'product'
]);
```



### Registration Event Tracking

You can also manually trigger registration events:

```php
use Paymenter\Extensions\Others\FacebookPixel\FacebookPixelHelper;

// Track user registration
FacebookPixelHelper::trackUserRegistration();

// Track user verification
FacebookPixelHelper::trackUserVerification();
```

## API Endpoints

### API Endpoints

- `POST /admin/extensions/facebook-pixel/test-event` - Send test event

### Test Event

Send a test event to verify your setup:

```bash
curl -X POST /admin/extensions/facebook-pixel/test-event \
  -H "Content-Type: application/json" \
  -d '{
    "event_name": "Purchase",
    "event_value": 29.99,
    "event_currency": "USD"
  }'
```

## Facebook Conversions API

The extension sends events directly to Facebook's Conversions API for improved tracking accuracy. Events are not stored locally and include:

- User data (hashed email, phone, external ID)
- Event details (name, time, source URL)
- Custom data (currency, value, content information)
- Technical data (IP address, user agent, Facebook cookies)

## Privacy & Compliance

- **Data Hashing**: User identifiers are hashed using SHA-256
- **GDPR Ready**: Respects user privacy preferences
- **Cookie Consent**: Integrates with Facebook's cookie requirements
- **Data Minimization**: Only sends necessary tracking data

## Troubleshooting

### Common Issues

1. **Events Not Sending**: Check your access token and pixel ID
2. **API Errors**: Verify your Facebook app permissions
3. **Test Events Failing**: Enable debug mode for detailed logs

### Debug Mode

Enable debug mode to see detailed API responses and event data in your application logs.

### Logs

Check your Laravel logs for Facebook Pixel related entries (only when debug mode is enabled):
- `storage/logs/laravel.log`

## Support

For issues or questions:
1. Check the logs for error messages
2. Verify your Facebook app configuration
3. Test with debug mode enabled
4. Ensure your access token has the required permissions

## Requirements

- Paymenter application
- PHP 8.0+
- Laravel 10+
- Valid Facebook Pixel ID
- Facebook App Access Token

## License

This extension is part of the Paymenter ecosystem and follows the same licensing terms.
