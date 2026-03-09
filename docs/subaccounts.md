# Subaccounts

The `SubAccountService` helps manage subaccounts for split payments, allowing you to automatically share transaction proceeds with other businesses.

## Creating a Subaccount

Register a new subaccount with a settlement bank.

```php
use Squipix\Paystack\Facades\Paystack;

$payload = [
    'business_name' => 'Partner Business',
    'settlement_bank' => 'Access Bank',
    'account_number' => '0123456789',
    'percentage_charge' => 10.5, // Share given to the subaccount
];

$response = Paystack::subAccount()->create($payload);
```

## Listing Subaccounts

Get a list of all subaccounts registered under your account.

```php
$subaccounts = Paystack::subAccount()->list();
```

## Fetching a Subaccount

Retrieve details of a specific subaccount using its code.

```php
$subaccount = Paystack::subAccount()->fetch('ACCT_xxxxxxxx');
```

## Updating a Subaccount

Modify the details of an existing subaccount.

```php
$payload = [
    'business_name' => 'Updated Business Name',
    'description' => 'Revised partner terms',
];

$response = Paystack::subAccount()->update('subaccount_code', $payload);
```
