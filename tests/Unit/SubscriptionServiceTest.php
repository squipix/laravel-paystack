<?php

namespace Squipix\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Facades\Paystack;
use Squipix\Paystack\Services\SubscriptionService;
use Squipix\Paystack\Test\TestCase;

class SubscriptionServiceTest extends TestCase
{
    public function testCreateSubscription(): void
    {
        Http::fake([
            'https://api.paystack.co/subscription' => Http::response(['status' => true, 'data' => ['email' => 'user@example.com']])
        ]);

        $response = Paystack::subscription()->create(
            [
                'email' => 'user@example.com'
            ]
        );
        
        $this->assertTrue($response['status']);
        $this->assertEquals('user@example.com', $response['data']['email']);
    }

    public function testDisableSubscription(): void
    {
        Http::fake([
            'https://api.paystack.co/subscription/disable' => Http::response(['status' => true])
        ]);

        $response = Paystack::subscription()->disable(['code' => 'SUB123']);

        $this->assertTrue($response['status']);
    }

    public function testEnableSubscription(): void
    {
        Http::fake([
            'https://api.paystack.co/subscription/enable' => Http::response(['status' => true])
        ]);

        $client = new PaystackClient();
        $service = new SubscriptionService($client);
        $response = $service->enable(['code' => 'SUB123']);

        $this->assertTrue($response['status']);
    }

    public function testFetchSubscription(): void
    {
        $code = 'SUB123';
        Http::fake([
            "https://api.paystack.co/subscription/{$code}" => Http::response(['status' => true, 'data' => ['subscription_code' => $code]])
        ]);

        // $client = new PaystackClient();
        // $service = new SubscriptionService($client);
        $response = paystack()->subscription()->fetch($code);

        $this->assertTrue($response['status']);
        $this->assertEquals($code, $response['data']['subscription_code']);
    }
}
