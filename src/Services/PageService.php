<?php

declare(strict_types=1);
// Paystack specific Documentation page website: https://paystack.com/docs/api/page/

namespace Squipix\Paystack\Services;

use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class PageService
 *
 * Handles Paystack Payment Page operations such as creating, fetching, and listing pages.
 *
 * @package Squipix\Paystack\Services
 */
class PageService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Squipix\Paystack\Client\PaystackClient
     */
    protected PaystackClient $client;

    /**
     * PageService constructor.
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
     * Create a new Payment Page on Paystack.
     *
     * Example:
     * ```php
     * [
     *     'name' => 'Special Offer',
     *     'description' => 'Limited time only',
     *     'amount' => 500000 // in kobo
     * ]
     * ```
     *
     * @param array<string, mixed> $payload Data required to create the page.
     * @return array<string, mixed>
     */
    public function create(array $payload = []): array
    {
        return $this->handle(fn () => $this->client->post('page', $payload)->json());
    }

    /**
     * Fetch a single Payment Page by its ID or slug.
     *
     * @param string $pageId The page ID or slug.
     * @return array<string, mixed>
     */
    public function fetch(string $pageId): array
    {
        return $this->handle(fn () => $this->client->get('page/' . urlencode($pageId))->json());
    }

    /**
     * Retrieve a list of all Payment Pages.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->handle(fn () => $this->client->get("page")->json());
    }

    /**
     * Update a payment page details.
     *
     * Example:
     * ```php
     * [
     *     "name": "Buttercup Brunch"
     *     'amount' => 10000
     *     'description' => 'Gather your friends for the ritual that is brunch'
     * ]
     * ```
     *
     * @param string $id_or_slug The ID/Slug.
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function update(string $id_or_slug, array $payload = []): array
    {
        return $this->handle(fn () => $this->client->put('page/' . urlencode($id_or_slug), $payload)->json());
    }

    /**
     * Check the availability of a slug for a payment page.
     *
     * @param string $slug URL slug to be confirmed
     * @return array<string, mixed>
     */
    public function checkSlugAvailability(string $slug)
    {
        return $this->handle(fn () => $this->client->get('page/check_slug_availability/' . urlencode($slug))->json());
    }

    /**
     * Add products to a payment page
     *
     * Example:
     * ```php
     * [
     *     "product" => [473, 292]
     * ]
     * ```
     *
     * @param string $id The product ID.
     * @param array<string, mixed> $payload The fields to update.
     * @return array<string, mixed>
     */
    public function addProducts(string $id, array $payload = [])
    {
        return $this->handle(fn () => $this->client->post('page/' . urlencode($id) . '/product', $payload)->json());
    }
}
