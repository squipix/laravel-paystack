#!/bin/bash

echo "Starting setup for Laravel-paystack Package..."

# Exit on any error
set -e

# Ensure composer is available
if ! command -v composer &> /dev/null
then
  echo "Composer not found. Please install Composer."
  exit
fi

# Install dependencies
echo "Installing Composer dependencies..."
composer install

# Copy .env.testing if it doesn’t exist
if [ ! -f .env.testing ]; then
  echo "Copying .env.testing.example to .env.testing"
  cp .env.testing.example .env.testing
else
  echo ".env.testing already exists, skipped."
fi

# Migrate PHPUnit configuration if needed
echo "Migrating PHPUnit configuration..."
vendor/bin/phpunit --migrate-configuration

echo "✅ Setup complete!"
