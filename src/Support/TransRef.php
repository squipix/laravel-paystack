<?php

declare(strict_types=1);

namespace Squipix\Paystack\Support;

/**
 * Class TransRef
 *
 * Generates a unique transaction reference string.
 *
 * @package Squipix\Paystack\Support
 */
class TransRef
{
    /**
     * Generate a unique transaction reference.
     *
     * Format: TXN_<unique_id>_<random_hex>
     *
     * @return string
    */
    public static function generate(): string
    {
        return 'TXN_' . uniqid() . '_' . bin2hex(random_bytes(4));
    }
}
