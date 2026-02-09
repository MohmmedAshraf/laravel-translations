<?php

use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Rules\TranslationParametersRule;

it('passes when translation key has no parameters', function () {
    $key = TranslationKey::factory()->create(['parameters' => null]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'Hello world'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when value is empty', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => ''],
        ['value' => ['nullable', $rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when single colon param is present', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'Hello :name!'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('fails when single colon param is missing', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'Hello world!'],
        ['value' => [$rule]],
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('value'))->toContain(':name');
});

it('fails listing only missing params when some are present', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute', ':max']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'The :attribute field is invalid.'],
        ['value' => [$rule]],
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('value'))->toContain(':max')
        ->and($validator->errors()->first('value'))->not->toContain(':attribute');
});

it('passes with mixed colon and brace style params', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name', '{count}']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'Hello :name, you have {count} items.'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when params appear inside plural string with selectors', function () {
    $key = TranslationKey::factory()->create([
        'parameters' => [':count'],
        'is_plural' => true,
    ]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => '{0} No items|{1} :count item|[2,*] :count items'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when param is at start of string', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => ':name logged in'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when param is at end of string', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':name']]);

    $rule = new TranslationParametersRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'Welcome :name'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('findMissing returns missing params', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute', ':max']]);

    $missing = TranslationParametersRule::findMissing($key, 'The :attribute field is invalid.');

    expect($missing)->toBe([':max']);
});

it('findMissing returns empty array when all present', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute', ':max']]);

    $missing = TranslationParametersRule::findMissing($key, ':attribute must be at most :max');

    expect($missing)->toBe([]);
});

it('findMissing returns all params when none present', function () {
    $key = TranslationKey::factory()->create(['parameters' => [':attribute', ':max']]);

    $missing = TranslationParametersRule::findMissing($key, 'Invalid field');

    expect($missing)->toBe([':attribute', ':max']);
});
