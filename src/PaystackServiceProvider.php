<?php

declare(strict_types=1);

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Squipix\Paystack;

use Illuminate\Support\ServiceProvider;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Services\BankService;
use Squipix\Paystack\Services\CustomerService;
use Squipix\Paystack\Services\PageService;
use Squipix\Paystack\Services\PlanService;
use Squipix\Paystack\Services\SubAccountService;
use Squipix\Paystack\Services\SubscriptionService;
use Squipix\Paystack\Services\TransactionService;

class PaystackServiceProvider extends ServiceProvider
{
    /**
    * Publishes all the config file this package needs to function
    * @return void
    */
    public function boot(): void
    {
        $this->bootConfig();
        // $this->bootViews(); // TODO
        // $this->bootRoutes(); // TODO
    }

    /**
     * Register the Paystack service and merge package configuration.
     *
     * This method binds the main Paystack class into the service container
     * and merges the package's config file with the application's config.
     *
     * @return void
    */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paystack.php', 'paystack');

        $this->app->singleton(PaystackClient::class, function () {
            return new PaystackClient(config('paystack.secretKey'));
        });

        $this->app->singleton(TransactionService::class, fn ($app) => new TransactionService($app->make(PaystackClient::class)));
        $this->app->singleton(CustomerService::class, fn ($app) => new CustomerService($app->make(PaystackClient::class)));
        $this->app->singleton(PlanService::class, fn ($app) => new PlanService($app->make(PaystackClient::class)));
        $this->app->singleton(SubscriptionService::class, fn ($app) => new SubscriptionService($app->make(PaystackClient::class)));
        $this->app->singleton(PageService::class, fn ($app) => new PageService($app->make(PaystackClient::class)));
        $this->app->singleton(SubAccountService::class, fn ($app) => new SubAccountService($app->make(PaystackClient::class)));
        $this->app->singleton(BankService::class, fn ($app) => new BankService($app->make(PaystackClient::class)));

        $this->app->singleton(Paystack::class, function ($app) {
            return new Paystack($app->make(PaystackClient::class));
        });

        // Bind the alias needed by the Facade
        $this->app->alias(Paystack::class, 'laravel-paystack');
    }


    /**
    * Get the services provided by the provider
    * @return array<int, class-string>
    */
    public function provides(): array
    {
        return [
            PaystackClient::class,
            TransactionService::class,
            CustomerService::class,
            PlanService::class,
            SubscriptionService::class,
            PageService::class,
            SubAccountService::class,
            BankService::class,
            Paystack::class,
        ];
    }

    /**
     * Publish the Paystack configuration file to the application's config directory.
     *
     * This allows users to override the default package configuration
     * by running: php artisan vendor:publish --tag=paystack-config
     *
     * @return void
    */
    protected function bootConfig()
    {
        $this->publishes([
            __DIR__.'/../config/paystack.php' => config_path('paystack.php'),
        ], 'config');
    }

    /**
     * Load and optionally publish the Paystack views to the application's resources.
     *
     * This loads the views using the 'paystack::' namespace and allows users to
     * customize them by running: php artisan vendor:publish --tag=paystack-views
     *
     * @return void
    */
    protected function bootViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'paystack');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/paystack'),
        ], 'paystack-views');
    }

    /**
     * Load Paystack package routes.
     *
     * This method registers the routes defined in the package so
     * they are available in the host Laravel application.
     *
     * @return void
    */
    protected function bootRoutes()
    {
        if (config('paystack.enable_routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
    }

}
