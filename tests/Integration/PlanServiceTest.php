<?php
// Paystack specific Documentation page website: https://paystack.com/docs/api/plan/

namespace Squipix\Paystack\Test\Integration;

use Squipix\Paystack\Facades\Paystack;

class PlanServiceTest extends IntegrationTestCase
{
    public function testCreatePlanWithRealApi(): void
    {
        $payload = [
            'name' => 'Monthly Pro ' . uniqid(),
            'amount' => 1000000, // NGN 10,000 in kobo
            'interval' => 'monthly',
        ];

        $response = Paystack::plan()->create($payload);

        $this->assertTrue($response['status']);
        $this->assertEquals($payload['name'], $response['data']['name']);
    }

    public function testFetchPlanWithRealApi(): void
    {
        $payload = [
            'name' => 'Starter Plan ' . uniqid(),
            'amount' => 300000,
            'interval' => 'weekly',
        ];

        $created = Paystack::plan()->create($payload);
        $planCode = $created['data']['plan_code'];

        $fetched = Paystack::plan()->fetch($planCode);

        $this->assertTrue($fetched['status']);
        $this->assertEquals($planCode, $fetched['data']['plan_code']);
    }

    public function testListPlansWithRealApi(): void
    {
        $response = Paystack::plan()->list();

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
    }

    public function testUpdatePlanWithRealApi(): void
    {
        $payload = [
            'name' => 'Update Plan ' . uniqid(),
            'amount' => 500000,
            'interval' => 'monthly',
        ];
        
        $created = Paystack::plan()->create($payload);
        // dump($created);
        // print_r($created);
        $planCode = $created['data']['plan_code'];
        
        $update = Paystack::plan()->update($planCode, [
            'name' => 'Monthly retainer (renamed)'
        ]);

        $this->assertTrue($update['status']);
        $this->assertEquals('Plan updated. 0 subscription(s) affected', $update['message']);
    }
}
