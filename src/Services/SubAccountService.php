<?php

// Paystack specific Documentation page website: https://paystack.com/docs/api/subaccount/

namespace Squipix\Paystack\Services;

use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class SubAccountService
 *
 * Manages Paystack subaccounts for split payments.
 *
 * @package Squipix\Paystack\Services
 */
class SubAccountService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * SubAccountService constructor.
     *
     * @param \Squipix\Paystack\Client\PaystackClient $client
     */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Wrap API calls with exception handling.
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
     * Create a new subaccount on Paystack.
     *
     * Example:
     * ```php
     * [
     *     'business_name' => 'My Business',
     *     'settlement_bank' => 'Access Bank',
     *     'account_number' => '1234567890',
     *     'percentage_charge' => 10.5
     * ]
     * ```
     *
     * @param array<string, mixed> $payload Data for creating the subaccount.
     * @return array<string, mixed>
     */
    public function create(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('subaccount', $payload)->json());
    }

    /**
     * List all subaccounts.
     *
     * @param array<string, mixed> $params Optional query parameter
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get("subaccount", $params)->json());
    }

    /**
     * Fetch a specific subaccount by its code.
     *
     * @param string $subaccountCode The unique code of the subaccount.
     * @return array<string, mixed>
     */
    public function fetch(string $subaccountCode): array
    {
        return $this->handle(fn () => $this->client->get('subaccount/' . urlencode($subaccountCode))->json());
    }

    /**
     * Update a subaccount details.
     *
     * Example:
     * ```php
     * [
     *     "business_name" => "An-Nur Info Tech."
     *     'description' => 'Provide IT Service'
     * ]
     * ```
     *
     * @param string $id_or_code The ID/Plan code.
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function update(string $id_or_code, array $payload = []): array
    {
        return $this->handle(fn () => $this->client->put('subaccount/' . urlencode($id_or_code), $payload)->json());
    }
}
