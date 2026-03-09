# Getting Started

## Installation

Install the package via Composer:

```bash
composer require squipix/laravel-paystack
```

The package will automatically register its service provider and facade for Laravel 5.5 and above.

## Configuration

First, publish the configuration file to your `config` directory:

```bash
php artisan vendor:publish --provider="Squipix\Paystack\PaystackServiceProvider"
```

Next, add your Paystack credentials and other settings to your `.env` file:

```env
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
PAYSTACK_PAYMENT_URL=https://api.paystack.co
MERCHANT_EMAIL=your-email@example.com

# Optional: Retry settings for HTTP requests
PAYSTACK_RETRY_ATTEMPTS=3
PAYSTACK_RETRY_DELAY=150
```

## Basic Usage

You can use the `Paystack` facade to access the various services provided by the library.

```php
use Squipix\Paystack\Facades\Paystack;

// Example: Generate a unique transaction reference
$reference = Paystack::transRef();
```
