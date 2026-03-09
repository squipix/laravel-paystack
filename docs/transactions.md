# Transactions

The `TransactionService` handles all transaction-related operations. You can access it via the `Paystack` facade.

## Initializing a Transaction

To start a payment process, initialize a transaction.

```php
use Squipix\Paystack\Facades\Paystack;

$payload = [
    'email' => 'customer@example.com',
    'amount' => 10000, // Amount in kobo (e.g., 10000 = ₦100)
    'callback_url' => route('payment.callback'),
];

$response = Paystack::transaction()->initialize($payload);

if ($response['status']) {
    $authorizationUrl = $response['data']['authorization_url'];
    return redirect($authorizationUrl);
}
```

## Verifying a Transaction

After the customer completes the payment on Paystack, they will be redirected back to your `callback_url` with a transaction reference. Use this reference to verify the transaction status.

```php
$reference = $request->query('reference');

$response = Paystack::transaction()->verify($reference);

if ($response['status'] && $response['data']['status'] === 'success') {
    // Payment was successful
}
```

## Listing Transactions

Retrieve a list of transactions with optional pagination parameters.

```php
$params = [
    'perPage' => 20,
    'page' => 1,
];

$transactions = Paystack::transaction()->list($params);
```

## Fetching a Single Transaction

Get details of a specific transaction using its ID or reference.

```php
$transaction = Paystack::transaction()->fetch('T123456789');
```

## Charging an Authorization

For recurring payments, you can charge a customer's authorization code.

```php
$payload = [
    'email' => 'customer@example.com',
    'amount' => 5000,
    'authorization_code' => 'AUTH_xxxxxxxx',
];

$response = Paystack::transaction()->chargeAuthorization($payload);
```

## Partial Debit

Retrieve part of a payment from a customer.

```php
$payload = [
    'email' => 'customer@example.com',
    'amount' => 2000,
    'currency' => 'NGN',
];

$response = Paystack::transaction()->partialDebit($payload);
```

## Viewing Transaction Timeline

View the history of events for a specific transaction.

```php
$timeline = Paystack::transaction()->viewTransactionTimeline('reference_or_id');
```

## Transaction Totals

Get the total amount received on your account.

```php
$totals = Paystack::transaction()->transactionTotals();
```
