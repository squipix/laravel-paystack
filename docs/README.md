# Laravel Paystack SDK Documentation

Welcome to the documentation for the Laravel Paystack package. A simple, expressive Laravel wrapper around the Paystack API.

## Introduction

This package provides a fluent interface for interacting with Paystack's payment services, focusing on ease of use and developer experience in Laravel applications.

---

## Table of Contents

1.  **[Getting Started](getting-started.md)** - Installation, configuration, and basic setup instructions.
2.  **[Transactions](transactions.md)** - Guide on initializing, verifying, and managing payments.
3.  **[Customers](customers.md)** - Detailed instructions for managing customer records and identities.
4.  **[Plans & Subscriptions](plans-subscriptions.md)** - Managing recurring billing, plans, and individual subscriptions.
5.  **[Subaccounts](subaccounts.md)** - Handling split payments and partner subaccounts.
6.  **[Miscellaneous Services](miscellaneous.md)** - Bank listings, account resolution, and payment pages.

---

## Features

- **Fluent API**: Expressive and simple method calls.
- **Service-Oriented Architecture**: Modular components for each Paystack service.
- **Auto-Discovery**: Automatic registration for Laravel 5.5+.
- **Built-in Retry Logic**: Configurable attempts for failed HTTP requests.
- **Strong Typing**: IDE-friendly docblocks and type declarations.

---

## Testing

Run the test suite using PHPUnit:

```bash
composer test
```

> Note: Integration tests are opt-in. Set `PAYSTACK_RUN_INTEGRATION_TESTS=true`
> in `.env.testing` and provide valid `PAYSTACK_SECRET_KEY` / `PAYSTACK_PUBLIC_KEY` values.

## Contributing

Contributions are welcome! Please feel free to fork this repository and submit a pull request.

## Support

If you discover any security-related issues, please email `info@squipix.com` instead of using the issue tracker.
