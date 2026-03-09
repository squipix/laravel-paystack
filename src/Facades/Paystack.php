<?php

namespace Squipix\Paystack\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Squipix\Paystack\Paystack
 *
 * @method static \Squipix\Paystack\Services\BankService bank()
 * @method static \Squipix\Paystack\Services\TransactionService transaction()
 * @method static \Squipix\Paystack\Services\CustomerService customer()
 * @method static \Squipix\Paystack\Services\PageService page()
 * @method static \Squipix\Paystack\Services\PlanService plan()
 * @method static \Squipix\Paystack\Services\SubAccountService subAccount()
 * @method static \Squipix\Paystack\Services\SubscriptionService subscription()
 * @method static \Squipix\Paystack\Support\TransRef transRef()
 */
class Paystack extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-paystack';
    }
}
