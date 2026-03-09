<?php
// Paystack specific Documentation page website: https://paystack.com/docs/api/customer/

namespace Squipix\Paystack\Test\Integration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Squipix\Paystack\Facades\Paystack;
use Squipix\Paystack\Services\CustomerService;

class CustomerServiceTest extends IntegrationTestCase
{
    protected CustomerService $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = $this->app->make(CustomerService::class);
    }

    public function testCreateCustomerWithRealApi(): void
    {
        $email = strtolower('test_' . Str::random(5) . '@example.com');
        
        $response = $this->customer->create([
            'email' => $email,
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'phone' => '08011112222'
        ]);

        $this->assertTrue($response['status']);
        $this->assertEquals($email, $response['data']['email']);

        // Store for other tests
        $GLOBALS['__customer_code'] = $response['data']['customer_code'];
    }

    public function testFetchCustomerWithRealApi(): void
    {
        $this->testCreateCustomerWithRealApi();
        $customerCode = $GLOBALS['__customer_code'];

        $response = $this->customer->fetch($customerCode);

        $this->assertTrue($response['status']);
        $this->assertEquals($customerCode, $response['data']['customer_code']);
    }

    public function testUpdateCustomerWithRealApi(): void
    {
        $this->testCreateCustomerWithRealApi();
        $customerCode = $GLOBALS['__customer_code'];

        $response = $this->customer->update($customerCode, [
            'first_name' => 'Updated'
        ]);

        $this->assertTrue($response['status']);
        $this->assertEquals('Updated', $response['data']['first_name']);
    }

    public function testListCustomers(): void
    {
        $response = $this->customer->list(['perPage' => 10, 'page' => 1]);

        $this->assertTrue($response['status']);
        $this->assertArrayHasKey('data', $response);
        // $this->assertIsArray($response['data']);
    }

    public function testValidateCustomer(): void
    {
        $this->testCreateCustomerWithRealApi();
        $customerCode = $GLOBALS['__customer_code'];

        // dd($customerCode);
        $response = $this->customer->validateCustomer($customerCode, [
            'email' => 'customer@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'type' => 'bank_account',
            'account_number' => '0123456789',
            'country' => 'NG',
            'bvn' => '12345678901',
            'bank_code' => '058', // GTBank
        ]);

        // dd($response);

        $this->assertTrue($response['status']);
        $this->assertEquals('Customer Identification in progress', $response['message']);
    }

    public function testWhitelistOrBlacklistCustomer(): void
    {
        $email = 'test_blacklist@example.com';

        // First, create the customer
        $customer = $this->customer->create([
            'email' => $email,
            'first_name' => 'Block',
            'last_name' => 'List',
        ]);

        $code = $customer['data']['customer_code'];

        // Now blacklist
        $response = $this->customer->setRiskAction([
            'customer' => $code,
            'risk_action' => 'deny' // or 'allow' for whitelisting
        ]);

        $this->assertTrue($response['status']);
        $this->assertEquals('Customer updated', $response['message']);
    }

    public function testInitializeAuthorization(): void
    {
        $response = $this->customer->initializeAuthorization([
            'email' => 'ravi-' . uniqid() . '@example.com',
            'channel' => 'direct_debit', 
            'callback_url' => 'https://an-nur-info-tech.com/payment/callback', // Change this to your callback url
        ]);

        // Output for debugging during test
        // fwrite(STDERR, print_r($response, true));

        $this->assertTrue($response['status']);
        $this->assertEquals('Authorization initialized', $response['message']);
        $this->assertArrayHasKey('redirect_url', $response['data']);
        $this->assertArrayHasKey('reference', $response['data']);

        // Uncomment(file_put_contents()) to save reference for verification test (if running in same session)
        // file_put_contents(__DIR__ . '/auth_reference.json', json_encode([
        //     'reference' => $response['data']['reference']
        // ]));
    }
    
    // public function testVerifyAuthorization(): void // This method always returned 404 error from the Paystack API
    // {
    //     $path = __DIR__ . '/auth_reference.json';
    //     if (!file_exists($path)) {
    //         $this->markTestSkipped('Authorization reference not found. Run testInitializeAuthorization first.');
    //     }

    //     $data = json_decode(file_get_contents($path), true);
    //     $reference = $data['reference'];

    //     // Delay to give Paystack time to process the authorization
    //     sleep(2);

    //     $response = $this->customer->verifyAuthorization($reference);
    //     // dump($response);

    //     fwrite(STDERR, print_r($response, true));

    //     // $this->assertIsArray($response);
    //     $this->assertArrayHasKey('data', $response);
    //     $this->assertTrue($response['status']);
    //     $this->assertEquals($reference, $response['data']['authorization_code']);
    // }

    // public function testInitializeAndVerifyAuthorization(): void
    // {
    //     $response = $this->customer->initializeAuthorization([
    //         'email' => 'john.doe.' . uniqid() . '@example.com',
    //         'amount' => 5000,
    //         'channel' => 'direct_debit',
    //         'callback_url' => 'https://an-nur-info-tech.com/payment/callback', // Change this to your callback url
    //     ]);

    //     $this->assertTrue($response['status']);
    //     $this->assertArrayHasKey('reference', $response['data']);
    //     $reference = $response['data']['reference'];
    //     // dump('Reference:', $reference);

    //     // Give Paystack some time (optional sleep)
    //     sleep(2);

    //     $verifyResponse = $this->customer->verifyAuthorization($reference);

    //     $this->assertTrue($verifyResponse['status']);
    //     $this->assertEquals($reference, $verifyResponse['data']['reference']);
    // }
}
