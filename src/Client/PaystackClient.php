<?php

declare(strict_types=1);

namespace Squipix\Paystack\Client;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Squipix\Paystack\Exceptions\PaystackRequestException;

/**
 * Class PaystackClient
 *
 * Handles HTTP communication with the Paystack API.
 */
class PaystackClient
{
    protected string $baseUrl;
    protected string $secretKey;
    private const HTTP_VERSION = 1.1;
    private const METHOD_GET = 'get';
    private const METHOD_POST = 'post';
    private const METHOD_PUT = 'put';
    private const METHOD_DELETE = 'delete';


    /**
     * PaystackClient constructor.
     *
     * @param string|null $secretKey  Optional API secret key.
     * @param string|null $baseUrl    Optional API base URL.
     */
    public function __construct(?string $secretKey = null, ?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?: config('paystack.paymentUrl', 'https://api.paystack.co');
        $this->secretKey = $secretKey ?: config('paystack.secretKey');
    }

    /**
     * Get a configured HTTP client for sending requests to Paystack.
     *
     * @internal
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function client(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::retry(
            config('paystack.retry_attempts', 3),
            config('paystack.retry_delay', 150)
        )
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ])
            ->withOptions([
                'http_errors' => false,
                'version' => self::HTTP_VERSION,
                'verify'      => true, // Set it to false for local debug
            ]);
    }

    /**
     * Handle API response errors.
     *
     * @internal
     * @param Response $response
     * @return Response
     *
     * @throws PaystackRequestException
     */
    protected function handleErrors(Response $response): Response
    {
        if (! $response->successful()) {
            $message = $response->json('message') ?? 'Paystack request failed.';
            Log::error('Paystack API error', ['response' => $response->body()]);
            throw new PaystackRequestException($message, $response);
        }

        return $response;
    }

    /**
     * Make an HTTP request to Paystack.
     *
     * @internal
     * @param string $method
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @return Response
     *
     * @throws PaystackRequestException
     */
    protected function request(string $method, string $endpoint, array $data = []): Response
    {
        $allowedMethods = [self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE];
        $method = strtolower($method);

        if (! in_array($method, $allowedMethods, true)) {
            throw new \InvalidArgumentException("Unsupported HTTP method: {$method}");
        }

        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        // $response = $this->client()->{$method}($url, $data);

        $client = $this->client();

        $response = $method === 'get'
        ? $client->get($url, $data)
        : $client->{$method}($url, $data);

        return $this->handleErrors($response);
    }

    /**
     * Send a GET request to a Paystack API endpoint.
     *
     * @param string $endpoint
     * @param array<string, mixed> $queryParams Optional query parameters
     * @return Response
     *
     * @throws PaystackRequestException
     */
    public function get(string $endpoint, array $queryParams = []): Response
    {
        return $this->request(self::METHOD_GET, $endpoint, $queryParams);
    }

    /**
     * Send a POST request to a Paystack API endpoint.
     *
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @return Response
     *
     * @throws PaystackRequestException
     */
    public function post(string $endpoint, array $data): Response
    {
        return $this->request(self::METHOD_POST, $endpoint, $data);
    }

    /**
     * Send a PUT request to a Paystack API endpoint.
     *
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @return Response
     *
     * @throws PaystackRequestException
     */
    public function put(string $endpoint, array $data): Response
    {
        return $this->request(self::METHOD_PUT, $endpoint, $data);
    }

    /**
    * Send a DELETE request to a Paystack API endpoint.
    *
    * @param string $endpoint
    * @return Response
    *
    * @throws PaystackRequestException
    */
    public function delete(string $endpoint): Response
    {
        return $this->request(self::METHOD_DELETE, $endpoint);
    }
}
