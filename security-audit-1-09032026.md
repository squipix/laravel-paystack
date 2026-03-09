# Security Audit Report: squipix/laravel-paystack
**Date:** March 9, 2026
**Target:** `squipix/laravel-paystack` PHP Library

## Executive Summary
A comprehensive security review of the `squipix/laravel-paystack` package was conducted. The codebase design is generally sound, appropriately leveraging Laravel's HTTP client with secure defaults (e.g., SSL verification enabled). Secrets are handled correctly via the configuration and environment variables.

However, the audit identified a significant vulnerability related to **Path Traversal / API Endpoint Injection** due to missing URL encoding on user-supplied parameters. Additionally, a functional bug with potential security implications was discovered in the `SubAccountService`.

---

## Findings

### 1. Path Traversal / API Endpoint Injection (Medium-High Severity)

**Description:**
Across multiple service classes, methods that take path parameters (such as `$reference`, `$id`, `$customerCode`, `$slug`) concatenate these values directly into the HTTP request URI without URL encoding them. 

Because parameters like a transaction reference or a customized slug are usually derived from external input (such as a web hook payload or a URL query string like `?reference=...`), an attacker could supply path traversal sequences (e.g., `../../`) to manipulate the targeted API endpoint. While this is partially mitigated by the fact that the API base URL is fixed to Paystack, it still constitutes Server-Side Request Forgery against the Paystack API, potentially allowing attackers to access unrelated endpoints or trigger unexpected behavior that bypasses the host application's business logic.

**Affected Files/Methods (Examples):**
* `TransactionService::verify($reference)` -> `transaction/verify/{$reference}`
* `CustomerService::fetch($email_or_code)` -> `customer/{$email_or_code}`
* `PlanService::fetch($planCode)` -> `plan/{$planCode}`
* `PageService::checkSlugAvailability($slug)` -> `page/check_slug_availability/{$slug}`
 *(Note: This affects almost all `fetch`, `update`, and `verify` methods across the `Services\` namespace).*

**Recommendation:**
Wrap all parameters appended to URL paths in the `urlencode()` function. 
*Example Fix:*
```php
// Vulnerable
return $this->handle(fn () => $this->client->get("transaction/verify/{$reference}")->json());

// Secure
$encodedRef = urlencode($reference);
return $this->handle(fn () => $this->client->get("transaction/verify/{$encodedRef}")->json());
```

---

### 2. Incorrect Endpoint in SubAccountService::update (Medium Severity)

**Description:**
In `src/Services/SubAccountService.php`, the `update` method incorrectly attempts to issue a `PUT` request to the `plan/` endpoint instead of the `subaccount/` endpoint. 

```php
public function update(string $id_or_code, array $payload = []): array
{
    return $this->handle(fn () => $this->client->put("plan/{$id_or_code}", $payload)->json());
}
```

If an application provides an attacker with the ability to edit their "subaccount", this bug would instead cause a modifying request to be sent to a Paystack `plan`. If the ID matches an existing plan, the attacker could inadvertently modify subscription plans, bypassing intended access controls.

**Recommendation:**
Correct the endpoint path in `SubAccountService::update`.
```php
return $this->handle(fn () => $this->client->put("subaccount/{$id_or_code}", $payload)->json());
```

---

### 3. Missing Payload Validation (Low Severity)

**Description:**
The package's service methods do not perform strict validation on the structures or types of the data arrays (`$payload`) they accept before sending them to Paystack. While deferring validation to the API avoids duplicating logic, it can result in unexpected failures and makes the application strictly rely on Paystack to reject malformed requests. Data validation is ultimately left to the consumer's controllers.

**Recommendation:**
Document the required structure for each payload thoroughly in docblocks. For future releases, consider implementing Data Transfer Objects (DTOs) with strict typing instead of raw arrays.

---

## Security Best Practices Observed (Pass)

* **SSL Verification:** In `PaystackClient::client()`, the HTTP client correctly hardcodes SSL host and peer verification (`'verify' => true`). This secures the connection against Man-in-the-Middle (MITM) attacks.
* **Secrets Management:** The `PaystackClient` safely loads keys from Laravel's configuration framework (`config('paystack.secretKey')`), isolating secrets from the application source code.
* **HTTP Method Verification:** The `PaystackClient::request()` strictly validates HTTP methods against an allowlist before execution, preventing arbitrary HTTP verb injection.
