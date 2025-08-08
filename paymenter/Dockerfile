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

COPY add_to_pelican.patch .
RUN patch -p1 < add_to_pelican.patch
