<?php

// Paystack specific Documentation page website: https://paystack.com/docs/api/transaction/

namespace Squipix\Paystack\Services;

use Illuminate\Support\Facades\Request;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class TransactionService
 *
 * Handles transaction-related operations with the Paystack API.
 *
 * @package Squipix\Paystack\Services
 */
class TransactionService
{
    /**
     * Paystack HTTP client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * TransactionService constructor.
     *
     * @param \Squipix\Paystack\Client\PaystackClient $client
     */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Handle exceptions gracefully and format the result.
     *
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
     * Initialize a transaction.
     *
     * Required payload fields:
     * - email: Customer's email address.
     * - amount: Amount in kobo (10000 = ₦100).
     *
     * Example:
     * ```php
     * [
     *     'email' => 'user@example.com',
     *     'amount' => 10000,
     *     'callback_url' => 'https://yourapp.com/callback'
     * ]
     * ```
     *
     * @param array<string, mixed> $payload Transaction initialization data.
     * @return array<string, mixed>
     */
    public function initialize(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('transaction/initialize', $payload)->json());
    }

    /**
     * Verify a transaction by reference code.
     *
     * @param string $reference The transaction reference.
     * @return array<string, mixed>
     */
    public function verify(string $reference): array
    {
        return $this->handle(fn () => $this->client->get('transaction/verify/' . urlencode($reference))->json());
    }

    /**
     * List transactions with pagination support.
     *
     * @param array<string, mixed> $params Optional query parameter
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get("transaction", $params)->json());
    }

    /**
     * Fetch a single transaction by its ID or reference.
     *
     * @param int|string $id Transaction ID or reference string.
     * @return array<string, mixed>
     */
    public function fetch(int|string $id): array
    {
        return $this->handle(fn () => $this->client->get('transaction/' . urlencode((string) $id))->json());
    }

    /**
     * All authorizations marked as reusable can be charged with this endpoint whenever you need to receive payments
     *
     * Example:
     * ```php
     * [
     *     "email" => "customer@email.com",
     *      "amount" => "20000",
     *      "authorization_code" => "AUTH_72btv547"
     * ]
     * ```
     *
     * @param array<string, mixed> $payload The body params.
     * @return array<string, mixed>
     */
    public function chargeAuthorization(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post("transaction/charge_authorization", $payload)->json());
    }

    /**
     * View the timeline of a transaction
     *
     * @param string $id_or_reference
     * @return array<string, mixed>
     */
    public function viewTransactionTimeline(string $id_or_reference): array
    {
        return $this->handle(fn () => $this->client->get('transaction/timeline/' . urlencode($id_or_reference))->json());
    }

    /**
     * Total amount received on your account
     *
     * @param array<string, mixed> $params Optional query params.
     * @return array<string, mixed>
     */
    public function transactionTotals(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get("transaction/totals", $params)->json());
    }

    /**
     * Export a list of transactions carried out.
     *
     * @param array<string, mixed> $params Optional query params.
     * @return array<string, mixed>
     */
    public function exportTotal(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get("transaction/export", $params)->json());
    }

    /**
     * Retrieve part of a payment from a customer
     *
     * Example payload:
     * ```php
     * [
     *     "currency" => "NGN",
     *      "amount" => "20000",
     *      "email" => "customer@email.com"
     * ]
     * ```
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function partialDebit(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post("transaction/partial_debit", $payload)->json());
    }
}
