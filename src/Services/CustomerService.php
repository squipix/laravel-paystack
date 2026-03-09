<?php

declare(strict_types=1);
// Paystack specific Documentation page website: https://paystack.com/docs/api/customer/

namespace Squipix\Paystack\Services;

use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class CustomerService
 *
 * Handles Paystack operations related to customers such as creating, fetching, and listing.
 *
 * @package Squipix\Paystack\Services
 */
class CustomerService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * CustomerService constructor.
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
     * Create a new customer on Paystack.
     *
     * Example:
     * ```php
     * [
     *     'email' => 'customer@example.com',
     *     'first_name' => 'John',
     *     'last_name' => 'Doe',
     *     'phone' => '08012345678'
     * ]
     * ```
     *
     * @param array<string, mixed> $data Customer data for creation.
     * @return array<string, mixed>
     */
    public function create(array $data = []): array
    {
        return $this->handle(fn () => $this->client->post('customer', $data)->json());
    }

    /**
     * Fetch a customer by their customer code.
     *
     * @param string $email_or_code The unique code identifying the customer.
     * @return array<string, mixed>
     */
    public function fetch(string $email_or_code): array
    {
        return $this->handle(fn () => $this->client->get('customer/' . urlencode($email_or_code))->json());
    }

    /**
     * Retrieve a paginated list of customers.
     *
     * @param array<string, mixed> $params Optional query parameters.
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get("customer", $params)->json());
    }

    /**
     * Update an existing customer.
     *
     * Example:
     * ```php
     * [
     *     'first_name' => 'UpdatedName',
     *     'last_name' => 'UpdatedLastName',
     *     'phone' => '08076543210'
     * ]
     * ```
     *
     * @param string $customerCode The unique customer code.
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function update(string $customerCode, array $payload = []): array
    {
        return $this->handle(fn () => $this->client->put('customer/' . urlencode($customerCode), $payload)->json());
    }

    /**
     * Validate a customer's identity.
     *
     * Example:
     * ```php
     * [
     *     'country' => 'NG',
     *     'type' => 'bank_account',
     *     'account_number' => '08076543210'
     *     'bvn' => '20012345677'
     * ]
     * ```
     *
     * @param string $customerCode The unique customer code.
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function validateCustomer(string $customerCode, array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('/customer/' . urlencode($customerCode) . '/identification', $payload)->json());
    }

    /**
     * Whitelist/Blaclist a customer
     *
     * Example:
     * ```php
     * [
     *      "customer" => "CUS_xr58yrr2ujlft9k",
     *      "risk_action" => "allow"
     * ]
     * ```
     *
     * @param array<string, mixed> $payload The field to update
     * @return array<string, mixed>
     */
    public function setRiskAction(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post("/customer/set_risk_action", $payload)->json());
    }

    /**
     * Initiate a request to create a reusable authorization code for recurring transactions.
     *
     * Example:
     * ```php
     * [
     *     'email' => 'ravi@demo.com',
     *     'channel' => 'direct_debit'
     *     'callback_url' => 'http://test.url.com'
     * ]
     * ```
     *
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function initializeAuthorization(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post("/customer/authorization/initialize", $payload)->json());
    }

    /**
     * Check the status of an authorization request.
     *
     * @param string $reference The reference returned in the initialization response
     * @return array<string, mixed>
     */
    public function verifyAuthorization(string $reference): array
    {
        return $this->handle(fn () => $this->client->get('/authorization/verify/' . urlencode($reference))->json());
    }

    // TODO's
    // Initialize Direct Debit
    // Direct Debit Activation Charge
    // Fetch Mandate Authorizations
    // Deactivate Authorization
}
