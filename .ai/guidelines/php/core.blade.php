# PHP

@php
    /** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
@if($assist->shouldEnforceStrictTypes())
    - Always use strict typing at the head of a `.php` file: `declare(strict_types=1);`.
@endif
- Always use curly braces for control structures, even for single-line bodies.

## Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
- <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
    protected function isAccessible(User $user, ?string $path = null): bool
    {
    ...
    }
</code-snippet>

## Enums
@if(empty($assist->enums()) || preg_match('/[A-Z]{3,8}/', $assist->enumContents()))
    - Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.
@else
    - That being said, keys in an Enum should follow existing application Enum conventions.
@endif

## Comments

- No comments, PHPDoc, or JSDoc (code should be self-documenting)
- Always use `use` statements for imports (never inline `\Namespace\Class`)
- Max 20 lines per function, early returns over nesting