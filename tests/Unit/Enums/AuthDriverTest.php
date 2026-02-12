<?php

use Outhebox\Translations\Enums\AuthDriver;

it('has two drivers', function () {
    expect(AuthDriver::cases())->toHaveCount(2);
});

it('has correct string values', function () {
    expect(AuthDriver::Contributors->value)->toBe('contributors')
        ->and(AuthDriver::Users->value)->toBe('users');
});

it('can be created from string', function () {
    expect(AuthDriver::from('contributors'))->toBe(AuthDriver::Contributors)
        ->and(AuthDriver::from('users'))->toBe(AuthDriver::Users);
});

it('returns null for invalid driver', function () {
    expect(AuthDriver::tryFrom('invalid'))->toBeNull();
});
