<?php

namespace Squipix\Paystack\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * Class PaystackRequestException
 *
 * Exception thrown when a Paystack API request fails.
 * Optionally stores the original HTTP response for further debugging.
 *
 * @package Squipix\Paystack\Exceptions
*/
class PaystackRequestException extends Exception
{
    /**
     * The HTTP client response from the failed request (if available).
     *
     * @var Response|null
    */
    protected ?Response $response;

    /**
     * Create a new PaystackRequestException instance.
     *
     * @param string $message The error message.
     * @param Response|null $response The original HTTP response from the failed request (optional).
    */
    public function __construct(string $message, ?Response $response = null)
    {
        parent::__construct($message);
        $this->response = $response;
    }

    /**
     * Get the HTTP client response from the failed request, if available.
     *
     * @return Response|null
    */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
