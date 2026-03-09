<?php

// Paystack specific Documentation page website: https://paystack.com/docs/api/plan/

namespace Squipix\Paystack\Services;

use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class PlanService
 *
 * Handles Paystack subscription plan operations such as creating, fetching, and listing plans.
 *
 * @package Squipix\Paystack\Services
 */
class PlanService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * PlanService constructor.
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
     * Create a new subscription plan on Paystack.
     *
     * Example:
     * ```php
     * [
     *     'name' => 'Monthly Gold Plan',
     *     'amount' => 100000, // in kobo
     *     'interval' => 'monthly',
     * ]
     * ```
     *
     * @param array<string, mixed> $payload The data required to create the plan.
     * @return array<string, mixed>
     */
    public function create(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('plan', $payload)->json());
    }

    /**
     * Fetch a subscription plan by its code.
     *
     * @param string $planCode The plan code from Paystack.
     * @return array<string, mixed>
     */
    public function fetch(string $planCode): array
    {
        return $this->handle(fn () => $this->client->get('plan/' . urlencode($planCode))->json());
    }

    /**
     * List all subscription plans with pagination.
     *
     * @param array<string, mixed> $params Optional Query parameters.
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get("plan", $params)->json());
    }

    /**
     * Update an existing plan.
     *
     * Example:
     * ```php
     * [
     *     "name" => "Monthly retainer (renamed)"
     *     'amount' => 10000
     * ]
     * ```
     *
     * @param string $id_or_code The ID/Plan code.
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function update(string $id_or_code, array $payload = []): array
    {
        return $this->handle(fn () => $this->client->put('plan/' . urlencode($id_or_code), $payload)->json());
    }
}
