<?php

namespace Workbench\App\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(CommandStarting::class, function ($event) {
            if (str_starts_with($event->command ?? '', 'boost:')) {
                app()->setBasePath(realpath(__DIR__.'/../../..'));
                app()->useAppPath(base_path('src'));

                config()->set('boost.code_environments.claude_code.guidelines_path', base_path('CLAUDE.md'));
            }
        });
    }
}
