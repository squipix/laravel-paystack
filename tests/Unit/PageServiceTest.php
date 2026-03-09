<?php

namespace Squipix\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Squipix\Paystack\Test\TestCase;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Services\PageService;

class PageServiceTest extends TestCase
{
    public function testCreatePaymentPage(): void
    {
        $payload = [
            'name' => 'Test Page',
            'description' => 'Test Description',
            'amount' => 5000,
        ];

        Http::fake([
            'https://api.paystack.co/page' => Http::response([
                'status' => true,
                'data' => [
                    'id' => 'pg123',
                    'name' => 'Test Page',
                    'description' => 'A test page',
                    'amount' => 5000,
                ],
                'message' => 'Page created successfully',
            ], 200)
        ]);

        $client = new PaystackClient();
        $service = new PageService($client);

        $response = $service->create($payload);

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
        $this->assertEquals('Page created successfully', $response['message']);
    }

    public function testFetchPaymentPage(): void
    {
        $pageId = 'abc123';

        Http::fake([
            "https://api.paystack.co/page/{$pageId}" => Http::response([
                'status' => true,
                'data' => [
                    'id' => $pageId,
                    'name' => 'Test Page',
                ],
            ], 200)
        ]);

        $client = new PaystackClient();
        $service = new PageService($client);

        $response = $service->fetch($pageId);

        $this->assertTrue($response['status']);
        $this->assertEquals($pageId, $response['data']['id']);
    }
    
    public function testListPaymentPages(): void
    {
        Http::fake([
            'https://api.paystack.co/page*' => Http::response([
                'status' => true,
                'data' => [
                    ['id' => 1, 'name' => 'Page 1'],
                    ['id' => 2, 'name' => 'Page 2'],
                ],
            ], 200)
        ]);

        $client = new PaystackClient();
        $service = new PageService($client);

        $response = $service->list();

        $this->assertTrue($response['status']);
        $this->assertCount(2, $response['data']);
    }

}
