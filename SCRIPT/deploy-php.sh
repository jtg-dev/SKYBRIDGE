#!/bin/bash
set -e

# Configuration
PHP_VERSION="7.4.32"
INSTALL_PREFIX="/opt/php-${PHP_VERSION}"
PHP_INI_DIR="${INSTALL_PREFIX}/etc"

# Install build dependencies
echo "Installing dependencies..."
if command -v apt-get &> /dev/null; then
    sudo apt-get update
    sudo apt-get install -y \
        build-essential \
        libxml2-dev \
        libcurl4-openssl-dev \
        libssl-dev \
        libonig-dev \
        unixodbc-dev \
        pkg-config \
        autoconf \
        bison \
        re2c \
        libsqlite3-dev
elif command -v yum &> /dev/null; then
    sudo yum groupinstall -y "Development Tools"
    sudo yum install -y \
        libxml2-devel \
        libcurl-devel \
        openssl-devel \
        oniguruma-devel \
        unixODBC-devel \
        sqlite-devel
fi

# Download PHP source
echo "Downloading PHP ${PHP_VERSION}..."
cd /tmp
wget https://www.php.net/distributions/php-${PHP_VERSION}.tar.gz
tar -xzf php-${PHP_VERSION}.tar.gz
cd php-${PHP_VERSION}

# Configure PHP with required extensions
echo "Configuring PHP..."
./configure \
    --prefix=${INSTALL_PREFIX} \
    --with-config-file-path=${PHP_INI_DIR} \
    --enable-mbstring \
    --with-curl \
    --with-openssl \
    --with-pdo-odbc=unixODBC,/usr \
    --with-unixODBC=/usr \
    --enable-cli \
    --enable-fpm

# Compile and install
echo "Compiling PHP (this may take a while)..."
make -j$(nproc)
sudo make install

# Create php.ini
echo "Creating php.ini..."
sudo mkdir -p ${PHP_INI_DIR}
sudo cp php.ini-production ${PHP_INI_DIR}/php.ini

# Set up PATH for all users
echo "Configuring PATH..."
sudo tee /etc/profile.d/php-custom.sh > /dev/null <<EOF
export PATH="${INSTALL_PREFIX}/bin:\$PATH"
EOF

sudo chmod +x /etc/profile.d/php-custom.sh

# Cleanup
cd /tmp
rm -rf php-${PHP_VERSION} php-${PHP_VERSION}.tar.gz

echo "PHP ${PHP_VERSION} installed successfully!"
echo "Installed to: ${INSTALL_PREFIX}"
echo ""
echo "To use immediately in this session, run:"
echo "  source /etc/profile.d/php-custom.sh"
echo ""
echo "Verify installation:"
echo "  ${INSTALL_PREFIX}/bin/php -v"
echo "  ${INSTALL_PREFIX}/bin/php -m | grep -E 'curl|mbstring|odbc|openssl|pdo_odbc'"