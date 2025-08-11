# Use the official Paymenter image as the base
FROM ghcr.io/paymenter/paymenter:latest

# Define the commit hash as an argument for easy updates
ARG RAZORPAY_COMMIT=f20ad39e61ead094661b880a3ee234926e767f57

# Install git, clone the repo, checkout the specific commit, and clean up
RUN apk add --no-cache git && \
    # Clone the Razorpay extension
    git clone https://github.com/Sarthak-07/razorpay-payment-gateway.git /app/extensions/Gateways/Razorpay && \
    # Change into the directory and checkout the pinned commit
    cd /app/extensions/Gateways/Razorpay && \
    echo "Checking out Razorpay extension commit: ${RAZORPAY_COMMIT}" && \
    git checkout ${RAZORPAY_COMMIT} && \
    # Remove the .git folder to reduce image size
    rm -rf .git && \
    # Fix permissions for the web server user
    chown -R www-data:www-data /app/extensions/Gateways/Razorpay

COPY themes /app/themes
COPY extensions/Pelican /app/extensions/Servers/Pelican
COPY extensions/Pages /app/extensions/Others/Pages

RUN chmod -R 755 /app/themes


RUN apk add --no-cache nodejs npm

RUN npm install && npm run build veltrixv2 && npm run build lucentui

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

# Remove all languages except English from the lang folder
RUN if [ -d "/app/lang" ]; then \
        echo "Removing all languages except English from lang folder"; \
        find /app/lang -maxdepth 1 -type d ! -name "en" ! -name "lang" -exec rm -rf {} +; \
        echo "Languages removed successfully"; \
    else \
        echo "Lang folder not found, skipping language cleanup"; \
    fi

COPY assets /app/public/assets/extended
