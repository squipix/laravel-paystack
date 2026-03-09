<?php

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /**
     * Public Key From Paystack Dashboard
     *
     */
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),

    /**
     * Secret Key From Paystack Dashboard
     *
     */
    'secretKey' => env('PAYSTACK_SECRET_KEY'),

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL'),

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => env('MERCHANT_EMAIL'),

    // Maximum retry attempts for HTTP client (default: 3)
    'retry_attempts' => env('PAYSTACK_RETRY_ATTEMPTS', 3),

    // Delay (ms) between retry attempts (default: 150)
    'retry_delay' => env('PAYSTACK_RETRY_DELAY', 150),

    /*
    |--------------------------------------------------------------------------
    | Enable Package Routes - Feature
    |--------------------------------------------------------------------------
    |
    | This option controls whether the Paystack package should automatically
    | load its built-in web routes. You may disable this if you prefer
    | to define your own routes or extend the functionality manually.
    |
    | Default: false
    |
    */
    'enable_routes' => false,
];
