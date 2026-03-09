<?php

namespace Squipix\Paystack\Test;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Squipix\Paystack\Facades\Paystack;
use Squipix\Paystack\PaystackServiceProvider;


/**
 * Base TestCase for Paystack package unit and feature tests.
 *
 * This class sets up the testing environment using Orchestra Testbench,
 * which allows for testing Laravel packages without requiring a full Laravel application.
 *
 * Responsibilities:
 * - Registers package service provider and facade
 * - Sets required configuration like Paystack secret key
 *
 * @package Squipix\Paystack\Test
*/
abstract class TestCase extends BaseTestCase
{
    /**
     * Register package service providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
    */
    protected function getPackageProviders($app): array
    {
        return [
            PaystackServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
    */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);
        
        if (file_exists(__DIR__ . '/../.env.testing')) {
            Dotenv::createImmutable(__DIR__ . '/../', '.env.testing')->load();
        }

        // Set Paystack config from env.testing
        $app['config']->set('paystack.secretKey', $_ENV['PAYSTACK_SECRET_KEY'] ?? env('PAYSTACK_SECRET_KEY'));
        $app['config']->set('paystack.publicKey', $_ENV['PAYSTACK_PUBLIC_KEY'] ?? env('PAYSTACK_PUBLIC_KEY'));
        $app['config']->set('paystack.paymentUrl', $_ENV['PAYSTACK_PAYMENT_URL'] ?? 'https://api.paystack.co');
    }


    /**
     * Register package aliases (facades).
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<string, class-string>
    */
    protected function getPackageAliases($app)
    {
        return [
            'Paystack' => Paystack::class,
        ];
    }
}
