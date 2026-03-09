# Miscellaneous Services

The library provides additional services for bank-related operations and payment page management.

## Bank Service

The `BankService` is used for retrieving bank lists and verifying account details.

### Listing Banks

Get a list of all supported banks and their properties.

```php
use Squipix\Paystack\Facades\Paystack;

$banks = Paystack::bank()->list();
```

### Listing Countries and States

Get lists of supported countries and states for address verification.

```php
// List countries
$countries = Paystack::bank()->listCountry();

// List states for a country (use country code from charge request)
$states = Paystack::bank()->listState($countryCode);
```

### Resolving Bank Accounts

Confirm that an account number belongs to the right customer.

```php
$params = [
    'account_number' => '0123456789',
    'bank_code' => '058',
];

$response = Paystack::bank()->resolveAccount($params);
```

---

## Payment Page Service

The `PageService` allows you to create and manage custom payment pages hosted by Paystack.

### Creating a Payment Page

Create a simple payment page for your products or services.

```php
$payload = [
    'name' => 'Special Anniversary Offer',
    'description' => 'Get our exclusive collection today!',
    'amount' => 1000000, // ₦10,000 in kobo
];

$response = Paystack::page()->create($payload);
```

### Listing and Fetching Pages

```php
// List all pages
$pages = Paystack::page()->list();

// Fetch a single page by ID or slug
$page = Paystack::page()->fetch('slug-or-id');
```

### Checking Slug Availability

```php
$status = Paystack::page()->checkSlugAvailability('your-custom-slug');
```

### Adding Products to a Page

```php
$payload = [
    'product' => [473, 292], // Product IDs
];

$response = Paystack::page()->addProducts('page_id', $payload);
```
