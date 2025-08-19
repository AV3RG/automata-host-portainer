# Use the official Paymenter image as the base
FROM ghcr.io/paymenter/paymenter:v1.2.10

RUN composer require razorpay/razorpay:^2.9 facebook/php-business-sdk:^18.0 league/iso3166:^4.0

# Copy our enhanced Razorpay extension with subscription support
COPY extensions/Razorpay-Paymenter /app/extensions/Gateways/Razorpay

# Copy themes and other extensions
COPY themes /app/themes
COPY extensions/Pelican /app/extensions/Servers/Pelican
COPY extensions/Pages /app/extensions/Others/Pages
COPY extensions/FAQ /app/extensions/Others/FAQ
COPY extensions/InvoicePDF /app/extensions/Others/InvoicePDF
COPY extensions/FacebookPixel /app/extensions/Others/FacebookPixel
COPY extensions/Others/ProductTags /app/extensions/Others/ProductTags

# Set proper permissions
RUN chmod -R 755 /app/themes && \
    chmod -R 755 /app/extensions/Gateways/Razorpay && \
    chmod -R 755 /app/extensions/Servers/Pelican && \
    chmod -R 755 /app/extensions/Others/Pages

# Install Node.js and npm for theme building
RUN apk add --no-cache nodejs npm

# Build themes
RUN npm install && npm run build veltrixv2 && npm run build lucentui

# Install patch utility for applying patches
RUN apk add --no-cache patch

# Copy all patches and apply them automatically
COPY patches/ /tmp/patches/
RUN for patch_file in /tmp/patches/*.patch; do \
        if [ -f "$patch_file" ]; then \
            echo "Applying patch: $(basename "$patch_file")"; \
            if ! patch -p1 < "$patch_file"; then \
                echo "ERROR: Failed to apply patch: $(basename "$patch_file")"; \
                exit 1; \
            fi; \
            echo "Successfully applied patch: $(basename "$patch_file")"; \
        fi; \
    done && \
    rm -rf /tmp/patches/

COPY assets /app/public/assets/extended

# Remove all languages except English from the lang folder
RUN find /app/lang -type d -mindepth 1 -maxdepth 1 ! -name 'en' -exec rm -rf {} + 2>/dev/null || true
