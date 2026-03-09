# Laravel Paystack SDK

A simple, expressive Laravel wrapper around the Paystack API.

---

## Setup

After cloning the repo, run:

```
./setup.sh
```

#  Composer Scripts
To run unit and integration tests (requires PAYSTACK_SECRET_KEY):
```
composer test
```

View unused dependencies:
```
composer unused
```

Automatically fix coding style issues:
```
composer php-fix
```

Run static analysis:
```
comoposer analyse
```

---

## Features

- Simple Laravel-style service and facade structure
- Fully modular service classes (e.g., `TransactionService`, `CustomerService`, etc.)
- Simple and expressive API: `$paystack->transaction()->initialize([...])`
- Strong type declarations and IDE-friendly docblocks
- Auto-generated transaction references with `transRef()`
- Built-in error handling and retry logic
- PSR-4 compliant and fully testable
- Built-in retry logic (configurable with PAYSTACK_RETRY_ATTEMPTS and PAYSTACK_RETRY_DELAY)

---

## Installation

Install via Composer:

```bash
composer require squipix/laravel-paystack
```
> Laravel auto-discovers the package. No manual registration needed.

---

## Configuration
Publish the config file:
```bash
php artisan vendor:publish --provider="Squipix\Paystack\PaystackServiceProvider"
```
Update your `.env`:
```
PAYSTACK_PUBLIC_KEY=pk_test_xxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxx
PAYSTACK_PAYMENT_URL=https://api.paystack.co
PAYSTACK_CALLBACK_URL=https://example.com/payment/callback
MERCHANT_EMAIL='example@example.com'

# Optional retry settings
PAYSTACK_RETRY_ATTEMPTS=3
PAYSTACK_RETRY_DELAY=150
```
---

## Usage
### Transaction
```
use Paystack;

$response = Paystack::transaction()->initialize([
    'email' => 'user@example.com',
    'amount' => 200000, // Amount in kobo
]);

$authorizationUrl = $response['data']['authorization_url'];
```
### Customer
```
$customer = Paystack::customer()->create([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe'
]);
```
### Plan
```
$plan = Paystack::plan()->create([
    'name' => 'Premium Monthly',
    'amount' => 5000 * 100,
    'interval' => 'monthly'
]);
```
### Controller Example
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Squipix\Paystack\Facades\Paystack;

/**
 * Class PaymentController
 *
 * Handles Paystack payment initialization, redirection, and callback verification.
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * Display the payment form to the user.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        return view('payments.index');
    }

    /**
     * Initialize a Paystack transaction and redirect to the authorization URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
    */
    public function redirectToGateway(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:100',
            'description' => 'nullable|string',
        ]);

        $reference = Paystack::transRef();

        $payload = [
            'email' => $request->email,
            'amount' => $request->amount * 100,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'custom_fields' => [
                    [
                        'display_name' => 'Name',
                        'variable_name' => 'name',
                        'value' => $request->name
                    ],
                    [
                        'display_name' => 'Description',
                        'variable_name' => 'description',
                        'value' => $request->description
                    ]
                ]
            ]
        ];

        try {
            $response = Paystack::transaction()->initialize($payload);
            $transAuthURL = $response['data']['authorization_url']; 
            
            return redirect($transAuthURL);
        } catch (\Exception $e) {
            Log::error('Paystack Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Failed to initiate payment.');
        }
    }

    /**
     * Handle the callback from Paystack after payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function handleGatewayCallback(Request $request)
    {
        $reference = $request->query('reference');

        try {
            $response = Paystack::transaction()->verify($reference);
            $data = $response['data'];

            // Here, you could store the payment record, send a receipt email, etc.
            return view('payments.success', ['payment' => $data]);
        } catch (\Exception $e) {
            Log::error('Verification Error', ['message' => $e->getMessage()]);
            return redirect('/payment')->with('error', 'Payment verification failed.');
        }
    }
}
```
> All methods return Laravel HTTP responses with decoded JSON.

### Route Example
```
Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment.form');
Route::post('/checkout', [App\Http\Controllers\PaymentController::class, 'redirectToGateway'])->name('checkout.process');
Route::get('/payment/callback', [App\Http\Controllers\PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');
```


| Service          | Description                     |
| ---------------- | ------------------------------- |
| `transaction()`  | Handle payment transactions     |
| `customer()`     | Manage customer records         |
| `plan()`         | Create and manage plans         |
| `subscription()` | Handle recurring subscriptions  |
| `bank()`         | Retrieve bank lists             |
| `page()`         | Manage payment pages.           |
| `subAccount()`   | Mangage subaccounts             |

---

## Testing
Run tests:
```
composer test
```
> Integration require PAYSTACK_SECRET_KEY in .env.testing.

---


