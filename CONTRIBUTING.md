# Contributing

Thanks for considering contributing to Laravel Translations!

## Setup

1. Fork the repo on GitHub, then clone your fork:

```bash
git clone git@github.com:MohmmedAshraf/laravel-translations.git && cd laravel-translations
```

2. Install dependencies:

```bash
composer install
npm install
```

3. Create a branch:

```bash
git checkout -b feature/your-feature-name
```

## Development

If you're working on frontend changes, run Vite in dev mode:

```bash
npm run dev
```

## Before Submitting

1. Format your code:

```bash
composer format
```

2. Run tests:

```bash
composer test
```

3. If you changed frontend files, build assets:

```bash
npm run build
```

## Testing Locally

You can test your local copy in a Laravel app by adding a path repository to the app's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/your/laravel-translations"
        }
    ]
}
```

Then run `composer update`.

## Pull Requests

- Write clear, descriptive commit messages
- Include context on what and why in the PR description
- Keep PRs focused — one feature or fix per PR
