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

use Squipix\Paystack\Services\TransactionService;
use Squipix\Paystack\Services\CustomerService;
use Squipix\Paystack\Services\PlanService;
use Squipix\Paystack\Services\SubscriptionService;
use Squipix\Paystack\Services\PageService;
use Squipix\Paystack\Services\SubAccountService;
use Squipix\Paystack\Services\BankService;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Support\TransRef;

/**
 * Paystack Service Container
 *
 * Provides access to Paystack services like Transaction, Customer, Plan, etc.
 * @package Squipix\Paystack
*/
class Paystack
{
    protected PaystackClient $client;

    /**
     * Create a new Paystack instance.
     *
     * @param  PaystackClient  $client
    */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the TransactionService instance.
     *
     * @return TransactionService
    */
    public function transaction(): TransactionService
    {
        return new TransactionService($this->client);
    }

    /**
     * Get the CustomerService instance.
     *
     * @return CustomerService
    */
    public function customer(): CustomerService
    {
        return new CustomerService($this->client);
    }

    /**
     * Get the PlanService instance.
     *
     * @return PlanService
    */
    public function plan(): PlanService
    {
        return new PlanService($this->client);
    }

    /**
     * Get the SubscriptionService instance.
     *
     * @return SubscriptionService
    */
    public function subscription(): SubscriptionService
    {
        return new SubscriptionService($this->client);
    }

    /**
     * Get the PageService instance.
     *
     * @return PageService
    */
    public function page(): PageService
    {
        return new PageService($this->client);
    }

    /**
     * Get the SubAccountService instance.
     *
     * @return SubAccountService
    */
    public function subAccount(): SubAccountService
    {
        return new SubAccountService($this->client);
    }

    /**
     * Get the BankService instance.
     *
     * @return BankService
    */
    public function bank(): BankService
    {
        return new BankService($this->client);
    }

    /**
     * Generate a unique transaction reference.
     *
     * @return string
    */
    public function transRef(): string
    {
        return TransRef::generate();
    }
}
