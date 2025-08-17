# Facebook Pixel Extension Troubleshooting

## Common Issues

### 1. "Class 'FacebookAds\Api' not found" Error

This error typically occurs when the Facebook PHP SDK is not properly installed or autoloaded.

#### Symptoms
- Error: `Class "FacebookAds\Api" not found`
- Facebook Pixel extension fails to initialize
- Events are not being sent to Facebook

#### Causes
- Facebook PHP SDK not installed via composer
- Autoloader not properly configured
- Docker container not rebuilt after dependency changes
- Incorrect file paths in Docker setup

#### Solutions

##### Option 1: Rebuild Docker Container
If you're using Docker, the Facebook SDK should be installed via the Dockerfile:

```bash
# Rebuild the Docker container to ensure dependencies are installed
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

##### Option 2: Manual Composer Installation
If running locally or if Docker installation failed:

```bash
# Navigate to your project root
cd /path/to/your/project

# Install Facebook SDK
composer require facebook/php-business-sdk:^18.0

# Regenerate autoloader
composer dump-autoload
```

##### Option 3: Check Autoloader Paths
The extension now includes fallback autoloader loading. Check if the autoloader exists at:
- `vendor/autoload.php`
- `extensions/vendor/autoload.php`
- `extensions/FacebookPixel/vendor/autoload.php`

##### Option 4: Run Debug Script
Use the included debug script to diagnose issues:

```bash
php extensions/FacebookPixel/debug_sdk.php
```

This will show:
- Laravel framework detection
- Composer autoloader location
- Facebook SDK class availability
- Vendor directory structure

#### Verification Steps

1. **Check if classes exist:**
```php
if (class_exists('FacebookAds\Api')) {
    echo "Facebook SDK is available";
} else {
    echo "Facebook SDK is NOT available";
}
```

2. **Check vendor directory:**
```bash
ls -la vendor/facebook/php-business-sdk/
```

3. **Check composer.json:**
```bash
cat composer.json | grep facebook
```

#### Configuration Requirements

Ensure these configuration values are set in your Facebook Pixel extension:
- `capi_access_token`: Your Facebook CAPI access token
- `pixel_id`: Your Facebook Pixel ID

#### Logging

The extension now includes comprehensive logging. Check your Laravel logs for:
- Facebook SDK availability status
- API initialization errors
- Event handling failures

#### Support

If issues persist:
1. Run the debug script and share output
2. Check Laravel logs for detailed error messages
3. Verify Docker container has been rebuilt
4. Ensure composer dependencies are properly installed

## File Structure

```
extensions/FacebookPixel/
├── CapiHelper.php          # Main helper class with error handling
├── FacebookPixel.php       # Main extension class
├── debug_sdk.php          # Diagnostic script
├── TROUBLESHOOTING.md     # This file
└── README.md              # Extension documentation
```

## Recent Improvements

- Added comprehensive error handling
- Implemented fallback autoloader loading
- Added SDK availability checks
- Improved logging and debugging
- Created diagnostic tools
