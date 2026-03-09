# Changelog

All Notable changes to `laravel-paystack` will be documented in this file

The format is inspired by [Keep a Changelog](https://keepachangelog.com) and this project adheres to [Semantic Versioning](https://semver.org).

---

## [Unreleased]

### Added
- Introduced `PaystackClient` class to abstract Guzzle HTTP requests.
- Configurable retry logic to `PaystackClient` via `retry_attempts` and `retry_delay` in `config/paystack.php`.
- Added service classes for clear separation of concerns:
  - `TransactionService`
  - `CustomerService`
  - `PlanService`
  - `SubscriptionService`
  - `PageService`
  - `SubAccountService`
  - `BankService`
- Implemented `TransRef` helper for generating transaction references (`Paystack::transRef()`).
- Wrote unit and integration tests for services using PHPUnit.
- Added `setup.sh` for bootstrapping the package setup.
- Added PHPDoc blocks to all service classes and methods to enhance developer experience and IDE support.

### Changed
- Refactored core `Paystack.php` class to follow SRP and delegate responsibilities to dedicated service classes.
- Centralized API logic through `PaystackClient` to improve testability and HTTP abstraction.
- Improved autoload structure for PSR-4 compliance.
- Restructured folders (`resources/config/paystack.php` → `config/paystack.php`).
- Enhanced PHPUnit configuration and improved test folder layout.
- Enhanced exception handling and removed reliance on global helpers like `request()` or `config()` in services.

### Removed
- Deprecated or unused logic from the core `Paystack` class.
- Obsolete configuration entries.
- Old and redundant test code.

### Fixed
- Resolved PSR-4 autoload warnings for tests.
- Fixed XML validation issue in `phpunit.xml` caused by invalid `<log>` structure.

---

## 2020-05-23

### Added
- Support for Laravel 7
- Support for splitting payments into subaccounts
- Support for more than one currency. Now you can use USD!
- Support for multiple quantities
- Support for helpers

---

## 2015-11-04

### Added
- Initial release
