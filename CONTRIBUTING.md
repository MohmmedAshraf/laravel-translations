# CONTRIBUTING

## Introduction

Hi there! We're thrilled that you'd like to contribute to this project. Your help is essential for keeping it great.

### 💻 Code Contribution

Please follow the steps below to contribute with code.

## Steps

### 📌 Step 1

Fork this repository and enter its directory.

Replace the placeholder `<YOUR-USERNAME>` with your GitHub username and run the command:

```shell
git clone https://github.com/<YOUR-USERNAME>/laravel-translations.git && cd laravel-translations
```

### 📌 Step 2

Install all PHP dependencies using Composer, run the command:

```shell
composer install
```

Once finished, proceed to install Node dependencies. Run the command:

```shell
npm install
```
or
```shell
yarn install
```

### 📌 Step 3

Create a new branch for your code. You may call it `feature-` / `fix-` / `enhancement-` followed by the name of what you are developing.

For example:

```shell
git checkout -b feature/feature-name
```

Now, you can work on this newly created branch.

> 💡 Tip: While developing, you may run the command `vite` to automatically rebuild any CSS and JavaScript files.


### 📌 Step 4

After you are done coding, please run Laravel Pint for code formatting:

```Shell
composer format
```

Finally, run the Pest PHP for tests:

```Shell
composer test
```

### 📌 Step 5

You may want to install your modified version of Laravel Translations UI inside a Laravel application, and test if it performs as expected.

In your Laravel application, modify the `composer.json` adding a `repositories` key with the `path` of Laravel Translations UI on your machine.

This will instruct composer to install Laravel Translations UI from your local folder instead of using the version on the official repository.

Example: `composer.json`

```json
{
    "require": {
        "outhebox/laravel-translations": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "/home/myuser/projects/laravel-translations"
        }
    ],
    "minimum-stability": "dev"
}
```

Proceed with `composer update`.

### 📌 Step 6

If you changed any CSS or JavaScript files, you must build the assets for production before committing.

Run the command:

```shell
vite build
```

### 📌 Step 7

Commit your changes. Please send short and descriptive commits.

For example:

```Shell
git commit -m "adds route for feature X"
```

### 📌 Step 8

If all tests are ✅ passing, you may push your code and submit a Pull Request.

Please write a summary of your contribution, detailing what you are changing/fixing/proposing.

When necessary, please provide usage examples, code snippets and screenshots. You may also include links related to Issues or other Pull Requests.

Once submitted, your Pull Request will be marked for review and people will send questions, comments and eventually request changes.

---

🙏 Thank you for your contribution!
