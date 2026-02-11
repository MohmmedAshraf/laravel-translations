<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Domain
    |--------------------------------------------------------------------------
    |
    | The domain under which the translation routes will be registered. Leave
    | null to use the same domain as your application. Set this when you want
    | translations to be accessible from a specific subdomain.
    |
    */

    'domain' => env('TRANSLATIONS_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    | The URI path prefix for all translation routes. For example, the default
    | value of "translations" means routes will be available at /translations.
    |
    */

    'path' => env('TRANSLATIONS_PATH', 'translations'),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | The middleware stack applied to all translation routes. You may add any
    | additional middleware your application requires for these routes.
    |
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | The database connection used for translation tables. When null, the
    | application's default database connection will be used.
    |
    */

    'database_connection' => env('TRANSLATIONS_DB_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Source Language
    |--------------------------------------------------------------------------
    |
    | The primary language your translation keys are written in. This language
    | serves as the base for all other translations in the system.
    |
    */

    'source_language' => env('TRANSLATIONS_SOURCE_LANGUAGE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Language Path
    |--------------------------------------------------------------------------
    |
    | The directory path where your application's language files are stored.
    | By default, this uses Laravel's lang_path() helper.
    |
    */

    'lang_path' => env('TRANSLATIONS_LANG_PATH', lang_path()),

    /*
    |--------------------------------------------------------------------------
    | Exclude Files
    |--------------------------------------------------------------------------
    |
    | An array of language file names to exclude from the import process. Any
    | files listed here will be skipped during scanning and importing.
    |
    */

    'exclude_files' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how users authenticate with the translation interface. The
    | "contributors" driver uses a built-in authentication system, while
    | "users" integrates with your application's existing auth system.
    |
    */

    'auth' => [
        'driver' => env('TRANSLATIONS_AUTH_DRIVER', 'contributors'),
        'model' => env('TRANSLATIONS_AUTH_MODEL', 'App\\Models\\User'),
        'guard' => env('TRANSLATIONS_AUTH_GUARD', 'web'),
        'login_url' => env('TRANSLATIONS_LOGIN_URL', '/login'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Import Settings
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of the translation import process.
    | You may toggle vendor scanning, parameter detection, HTML detection,
    | and plural form detection according to your needs.
    |
    */

    'import' => [
        'scan_vendor' => true,
        'detect_parameters' => true,
        'detect_html' => true,
        'detect_plural' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    |
    | These options control how translations are exported back to language
    | files. You may sort keys alphabetically and exclude empty translations.
    |
    */

    'export' => [
        'sort_keys' => true,
        'exclude_empty' => true,
        'require_approval' => env('TRANSLATIONS_REQUIRE_APPROVAL', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | Configure the queue connection and queue name used for background
    | translation jobs such as imports and exports.
    |
    */

    'queue' => [
        'connection' => env('TRANSLATIONS_QUEUE_CONNECTION'),
        'name' => env('TRANSLATIONS_QUEUE_NAME', 'translations'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    |
    | This option controls whether new contributor registration is enabled.
    | When disabled, only existing contributors may access the system.
    |
    */

    'registration' => env('TRANSLATIONS_REGISTRATION', true),

    /*
    |--------------------------------------------------------------------------
    | Default Contributor Role
    |--------------------------------------------------------------------------
    |
    | The default role assigned to new contributors when they are invited.
    | The first contributor created during install always receives the
    | "owner" role regardless of this setting.
    |
    */

    'default_role' => env('TRANSLATIONS_DEFAULT_ROLE', 'translator'),

    /*
    |--------------------------------------------------------------------------
    | Approval Workflow
    |--------------------------------------------------------------------------
    |
    | When enabled, translations saved by Translators enter a "needs_review"
    | status instead of "translated". Admins and Owners bypass the workflow
    | and their saves are automatically approved.
    |
    */

    'approval_workflow' => env('TRANSLATIONS_APPROVAL_WORKFLOW', true),

    /*
    |--------------------------------------------------------------------------
    | Invite Settings
    |--------------------------------------------------------------------------
    |
    | Configure the contributor invitation system. The expires_days setting
    | controls how long an invite link remains valid before expiring.
    |
    */

    'invite' => [
        'expires_days' => 7,
    ],

];
