<?php

namespace Squipix\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Squipix\Paystack\Services\CustomerService;
use Squipix\Paystack\Test\TestCase;
use Squipix\Paystack\Client\PaystackClient;

class CustomerServiceTest extends TestCase
{

    public function testCreateCustomer(): void
    {
        Http::fake([
            'https://api.paystack.co/customer' => Http::response(['status' => true, 'data' => ['email' => 'test@example.com']])
        ]);

        $client = new PaystackClient();
        $service = new CustomerService($client);
        $response = $service->create(['email' => 'test@example.com']);

        $this->assertTrue($response['status']);
        $this->assertEquals('test@example.com', $response['data']['email']);
    }

    public function testListCustomers(): void
    {
        Http::fake([
            'https://api.paystack.co/customer*' => Http::response(['status' => true, 'data' => [['email' => 'test@example.com']]])
        ]);

        $client = new PaystackClient();
        $service = new CustomerService($client);
        $response = $service->list();

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
    }

    public function testFetchCustomer(): void
    {
        $id = 'ABC12345';
        Http::fake([
            "https://api.paystack.co/customer/{$id}" => Http::response(['status' => true, 'data' => ['id' => $id]])
        ]);

        $client = new PaystackClient();
        $service = new CustomerService($client);
        $response = $service->fetch($id);

        $this->assertTrue($response['status']);
        $this->assertEquals($id, $response['data']['id']);
    }
}
