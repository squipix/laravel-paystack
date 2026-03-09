<?php

// Paystack specific Documentation page website: https://paystack.com/docs/api/subscription/

namespace Squipix\Paystack\Services;

use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class SubscriptionService
 *
 * Handles recurring billing via subscriptions on Paystack.
 *
 * @package Squipix\Paystack\Services
 */
class SubscriptionService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * SubscriptionService constructor.
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
     * Create a subscription between a customer and a plan.
     *
     * Example:
     * ```php
     * [
     *     'customer' => 'CUS_xxxxxxx',
     *     'plan' => 'PLN_xxxxxxx'
     * ]
     * ```
     *
     * @param array<string, mixed> $payload Subscription creation data.
     * @return array<string, mixed>
     */
    public function create(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('subscription', $payload)->json());
    }

    /**
     * List all subscriptions or filter them.
     *
     * Example:
     * ```php
     * [
     *     'customer' => 'CUS_xxxxxxx',
     *     'plan' => 'PLN_xxxxxxx',
     *     'perPage' => 50,
     *     'page' => 1
     * ]
     * ```
     *
     * @param array<string, mixed> $params Optional query parameters.
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get('subscription', $params)->json());
    }

    /**
     * Fetch details of a specific subscription by code.
     *
     * @param string $subscriptionCode The subscription code.
     * @return array<string, mixed>
     */
    public function fetch(string $subscriptionCode): array
    {
        return $this->handle(fn () => $this->client->get('subscription/' . urlencode($subscriptionCode))->json());
    }

    /**
     * Enable a subscription using code and token.
     *
     * Example:
     * ```php
     * [
     *     'code' => 'SUB_xxxxxxx',
     *     'token' => 'email_token_xxxxxxx'
     * ]
     * ```
     *
     * @param array<string, mixed> $payload Enable payload.
     * @return array<string, mixed>
     */
    public function enable(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('subscription/enable', $payload)->json());
    }

    /**
     * Disable a subscription by customer code or email token.
     *
     * Example:
     * ```php
     * [
     *     'code' => 'SUB_xxxxxxx',
     *     'token' => 'email_token_xxxxxxx'
     * ]
     * ```
     *
     * @param array<string, mixed> $payload Disable payload.
     * @return array<string, mixed>
     */
    public function disable(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('subscription/disable', $payload)->json());
    }

    /**
     * Generate a link for updating the card on a subscription.
     *
     * @param string $subscriptionCode The subscription code.
     * @return array<string, mixed>
     */
    public function generateUpdateSubscriptionLink(string $subscriptionCode): array
    {
        return $this->handle(fn () => $this->client->get('subscription/' . urlencode($subscriptionCode) . '/manage/link')->json());
    }

    /**
     * Email a customer a link for updating the card on their subscription
     *
     * @param string $subscriptionCode The subscription code.
     * @param array<string, mixed> $payload Body parameters.
     * @return array<string, mixed>
     */
    public function sendUpdateSubscriptionLink(string $subscriptionCode, array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('subscription/' . urlencode($subscriptionCode) . '/manage/email', $payload)->json());
    }
}
