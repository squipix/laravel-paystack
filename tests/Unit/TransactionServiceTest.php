<?php

namespace Squipix\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Squipix\Paystack\Client\PaystackClient;
use Squipix\Paystack\Services\TransactionService;
use Squipix\Paystack\Test\TestCase;
use Squipix\Paystack\Facades\Paystack;

class TransactionServiceTest extends TestCase
{
    public function testInitializeTransactionUsingFacade(): void
    {
        $mockReference = Str::uuid()->toString();

        Http::fake([
            'https://api.paystack.co/transaction*' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://paystack.com/pay/test',
                    'reference' => $mockReference
                ]
            ])
        ]);

        // Facade registered in TestCase
        $response = Paystack::transaction()->initialize([
            'email' => 'test@example.com',
            'amount' => 10000
        ]);

        $this->assertTrue($response['status']);
        $this->assertEquals('https://paystack.com/pay/test', $response['data']['authorization_url']);
    }

    public function testVerifyTransaction(): void
    {
        $reference = 'txn_ref_123';

        Http::fake([
            "https://api.paystack.co/transaction/verify/{$reference}" => Http::response([
                'status' => true,
                'message' => 'Verification successful',
            ])
        ]);

        $client = new PaystackClient('', '');
        $service = new TransactionService($client);
        $response = $service->verify($reference);

        $this->assertTrue($response['status']);
        $this->assertEquals('Verification successful', $response['message']);
    }

    public function testFetchTransaction(): void
    {
        $id = 7890;

        Http::fake([
            "https://api.paystack.co/transaction/{$id}" => Http::response([
                'status' => true,
                'data' => [
                    'id' => $id
                ],
            ])
        ]);

        $client = new PaystackClient();
        $service = new TransactionService($client);
        $response = $service->fetch($id);

        $this->assertTrue($response['status']);
        $this->assertEquals($id, $response['data']['id']);
    }

    public function testListPaginatedTransactions(): void
    {        
        Http::fake([
            "https://api.paystack.co/transaction*" => Http::response([
                'status' => true,
                'data' => [
                    ['id' => 101, 'amount' => 15000],
                    ['id' => 102, 'amount' => 25000],
                    ['id' => 103, 'amount' => 35000],
                ]
            ])
        ]);

        $client = new PaystackClient();
        $service = new TransactionService($client);
        $response = $service->list(['perPage' => 10, 'page' => 1]);

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
        $this->assertCount(3, $response['data']);
        $this->assertEquals(15000, $response['data'][0]['amount']);
        $this->assertEquals(25000, $response['data'][1]['amount']);
    }

    public function testGenerateTransactionReference(): void
    {
        $this->assertIsString(Paystack::transRef());
        $this->assertStringStartsWith('TXN_', Paystack::transRef());
        $this->assertGreaterThan(10, strlen(Paystack::transRef()));
    }
}
