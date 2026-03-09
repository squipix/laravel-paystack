# Plans and Subscriptions

Manage recurring billing by defining plans and subscribing customers to them.

## Subscription Plans

### Creating a Plan

Define a recurring payment schedule.

```php
use Squipix\Paystack\Facades\Paystack;

$payload = [
    'name' => 'Monthly Gold Plan',
    'amount' => 500000, // Amount in kobo (e.g., 500000 = ₦5,000)
    'interval' => 'monthly', // Options: hourly, daily, weekly, monthly, quarterly, biannually, annually
];

$response = Paystack::plan()->create($payload);
```

### Listing Plans

Get a list of all defined subscription plans.

```php
$plans = Paystack::plan()->list();
```

### Fetching a Plan

Retrieve details of a specific plan by its code.

```php
$plan = Paystack::plan()->fetch('PLN_xxxxxxxx');
```

### Updating a Plan

Modify the details of an existing plan.

```php
$payload = [
    'name' => 'Premium Monthly Retainer',
    'amount' => 1000000,
];

$response = Paystack::plan()->update('plan_code', $payload);
```

---

## Subscriptions

### Creating a Subscription

Subscribe a customer to a plan.

```php
$payload = [
    'customer' => 'CUS_xxxxxxx',
    'plan' => 'PLN_xxxxxxx',
];

$response = Paystack::subscription()->create($payload);
```

### Listing Subscriptions

List all subscriptions or filter them by customer or plan.

```php
$params = [
    'customer' => 'CUS_xxxxxxx',
    'plan' => 'PLN_xxxxxxx',
];

$subscriptions = Paystack::subscription()->list($params);
```

### Fetching a Subscription

Get details of a specific subscription.

```php
$subscription = Paystack::subscription()->fetch('SUB_xxxxxxx');
```

### Enabling/Disabling a Subscription

Manage the active status of a subscription.

```php
$payload = [
    'code' => 'SUB_xxxxxxx',
    'token' => 'email_token_xxxxxxx',
];

// Enable
$response = Paystack::subscription()->enable($payload);

// Disable
$response = Paystack::subscription()->disable($payload);
```

### Updating Subscription Card Info

Generate or send a link for a customer to update their saved card for a subscription.

```php
// Generate a link
$link = Paystack::subscription()->generateUpdateSubscriptionLink('SUB_xxxxxxx');

// Send link via email
$response = Paystack::subscription()->sendUpdateSubscriptionLink('SUB_xxxxxxx');
```
