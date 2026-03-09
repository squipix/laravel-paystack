<?php

namespace Squipix\Paystack\Test\Integration;

use Squipix\Paystack\Facades\Paystack;

class PageServiceTest extends IntegrationTestCase
{
    public function testCreatePageWithRealApi(): void
    {
        $title = 'Premium Membership ' . uniqid();
        $payload = [
            'name' => $title,
            'description' => 'Access premium blog content',
            'amount' => 500000, // NGN 5,000 in kobo
        ];

        $response = Paystack::page()->create($payload);

        $this->assertTrue($response['status']);
        $this->assertEquals($title, $response['data']['name']);
    }

    public function testFetchPageWithRealApi(): void
    {
        $title = 'Mini Page ' . uniqid();
        $payload = [
            'name' => $title,
            'description' => 'One-time offer',
            'amount' => 250000,
        ];

        $create = Paystack::page()->create($payload);
        $slug = $create['data']['slug'];

        $fetched = Paystack::page()->fetch($slug);

        $this->assertTrue($fetched['status']);
        $this->assertEquals($slug, $fetched['data']['slug']);
    }

    public function testListPagesWithRealApi(): void
    {
        $response = Paystack::page()->list();

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
    }

    // TODO's tests
    // public function testCheckSlugAvailability(): void{}
    // public function testAddProducts(): void{}
}
