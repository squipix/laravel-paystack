<?php

declare(strict_types=1);
// Paystack specific Documentation page website: https://paystack.com/docs/api/miscellaneous/#bank / https://paystack.com/docs/api/verification/

namespace Squipix\Paystack\Services;

use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class BankService
 *
 * Handles Paystack bank-related operations such as listing banks and resolving bank accounts.
 *
 * @package Squipix\Paystack\Services
 */
class BankService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * BankService constructor.
     *
     * @param \Squipix\Paystack\Client\PaystackClient $client
     */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Wrap API calls with error handling.
     *
     * @internal This method is not part of the public API and may change without notice.
     *
     * @param callable $callback
     * @return array{status: bool, message: string, data: mixed}
     */
    protected function handle(callable $callback): array
    {
        try {
            return $callback();
        } catch (PaystackRequestException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Get a list of all supported banks and their properties.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->handle(fn () => $this->client->get('bank')->json());
    }

    /**
     * Gets a list of countries that Paystack currently supports.
     *
     * @return array<string, mixed>
     */
    public function listCountry(): array
    {
        return $this->handle(fn () => $this->client->get('country')->json());
    }

    /**
     * Get a list of states for a country for address verification.
     *
     * @param int $countryCode The country code of the states to list. It is gotten after the charge request.
     * @return array<string, mixed>
    */
    public function listState(int $countryCode): array
    {
        return $this->handle(fn () => $this->client->get("address_verification/states?country={$countryCode}")->json());
    }

    /**
     * Confirm an account belongs to the right customer
     *
     * Example:
     * ```php
     * [
     *     'account_number' => '1234567890',
     *     'bank_code' => '058'
     * ]
     * ```
     *
     * @param array<string, mixed> $params Query parameter containing `account_number` and `bank_code`.
     * @return array<string, mixed>
    */
    public function resolveAccount(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get('bank/resolve', $params)->json());
    }
}
