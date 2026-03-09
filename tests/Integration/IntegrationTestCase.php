<?php

namespace Squipix\Paystack\Test\Integration;

use Squipix\Paystack\Test\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->skipUnlessIntegrationTestsEnabled();
    }

    protected function skipUnlessIntegrationTestsEnabled(): void
    {
        $runIntegration = filter_var(
            env('PAYSTACK_RUN_INTEGRATION_TESTS', false),
            FILTER_VALIDATE_BOOLEAN
        );

        if (! $runIntegration) {
            $this->markTestSkipped(
                'Integration tests are disabled. Set PAYSTACK_RUN_INTEGRATION_TESTS=true to enable live Paystack API tests.'
            );
        }

        $secretKey = (string) config('paystack.secretKey', '');
        $publicKey = (string) config('paystack.publicKey', '');

        if ($secretKey === '' || $publicKey === '') {
            $this->markTestSkipped('Integration tests require PAYSTACK_SECRET_KEY and PAYSTACK_PUBLIC_KEY.');
        }

        if ($this->looksLikePlaceholderKey($secretKey) || $this->looksLikePlaceholderKey($publicKey)) {
            $this->markTestSkipped('Integration tests require real Paystack credentials, not placeholder values.');
        }
    }

    protected function looksLikePlaceholderKey(string $key): bool
    {
        $normalized = strtolower(trim($key));

        if ($normalized === '') {
            return true;
        }

        return str_contains($normalized, 'mock')
            || str_contains($normalized, 'xxxx')
            || str_contains($normalized, 'your_')
            || str_contains($normalized, 'example');
    }
}