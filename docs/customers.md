# Customers

The `CustomerService` provides methods to manage customer records on Paystack.

## Creating a Customer

Create a new customer by offering their basic contact information.

```php
use Squipix\Paystack\Facades\Paystack;

$data = [
    'email' => 'customer@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '08012345678',
];

$response = Paystack::customer()->create($data);
```

## Listing Customers

Retrieve a paginated list of customers on your Paystack account.

```php
$params = [
    'perPage' => 50,
    'page' => 1,
];

$customers = Paystack::customer()->list($params);
```

## Fetching a Customer

Get details of a specific customer using their email or customer code.

```php
$customer = Paystack::customer()->fetch('CUS_xxxxxxxx');
```

## Updating a Customer

Modify the information for an existing customer.

```php
$payload = [
    'first_name' => 'UpdatedName',
    'phone' => '08076543210',
];

$response = Paystack::customer()->update('customer_code', $payload);
```

## Identity Validation

Validate a customer's identity for compliance purposes.

```php
$payload = [
    'country' => 'NG',
    'type' => 'bank_account',
    'account_number' => '0123456789',
    'bvn' => '20012345677',
];

$response = Paystack::customer()->validateCustomer('customer_code', $payload);
```

## Setting Risk Action

Whitelist or blacklist a customer to manage risk.

```php
$payload = [
    'customer' => 'customer_code',
    'risk_action' => 'allow', // or 'deny'
];

$response = Paystack::customer()->setRiskAction($payload);
```

## Recurring Payments Authorization

Initiate an authorization process for direct debit or recurring charges.

```php
$payload = [
    'email' => 'customer@example.com',
    'channel' => 'direct_debit',
    'callback_url' => 'https://yourapp.com/callback',
];

$response = Paystack::customer()->initializeAuthorization($payload);

// Later, verify the status of the authorization request
$reference = 'reference_from_initialization';
$status = Paystack::customer()->verifyAuthorization($reference);
```
