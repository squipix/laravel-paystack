<?php

namespace Squipix\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Services\PlanService;
use Squipix\Paystack\Test\TestCase;

class PlanServiceTest extends TestCase
{
    public function testCreatePlan(): void
    {
        Http::fake([
            'https://api.paystack.co/plan' => Http::response(['status' => true, 'data' => ['name' => 'Basic Plan']])
        ]);

        $client = new PaystackClient();
        $service = new PlanService($client);
        $response = $service->create(['name' => 'Basic Plan']);

        $this->assertTrue($response['status']);
        $this->assertEquals('Basic Plan', $response['data']['name']);
    }

    public function testListPlans(): void
    {
        Http::fake([
            'https://api.paystack.co/plan*' => Http::response(['status' => true, 'data' => [['name' => 'Basic Plan']]])
        ]);

        $client = new PaystackClient();
        $service = new PlanService($client);
        $response = $service->list();

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
    }

    public function testFetchPlan(): void
    {
        $id = 'ABC67890';
        Http::fake([
            "https://api.paystack.co/plan/{$id}" => Http::response(['status' => true, 'data' => ['id' => $id]])
        ]);

        $client = new PaystackClient();
        $service = new PlanService($client);
        $response = $service->fetch($id);

        $this->assertTrue($response['status']);
        $this->assertEquals($id, $response['data']['id']);
    }
}
