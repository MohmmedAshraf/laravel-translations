# Testing

## Locations

- `tests/Unit/Actions/` — Action unit tests
- `tests/Feature/Http/Controllers/{Domain}/` — HTTP tests
- `tests/Extraction/` — Extraction output tests

## Standards

- AAA: Arrange, Act, Assert
- Test happy path, errors, edge cases
- Test 403 (auth) and 422 (validation)

## Extraction Tests

```php
it('extracts correctly', function () {
    expect(extract(['subscriptions']))
        ->toContain('SubscriptionController')
        ->not->toContain('#feature:');
});
```

## Commands

```bash
php artisan test --compact
```
