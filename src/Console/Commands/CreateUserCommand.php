<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Password;
use Outhebox\Translations\Concerns\DisplayHelper;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:create-user
        {--name= : The name of the contributor}
        {--email= : The email of the contributor}
        {--role= : The role (owner, admin, translator, reviewer, viewer)}';

    protected $description = 'Create a new translations contributor';

    public function handle(): int
    {
        $this->displayHeader('Create User');

        $name = $this->collectName();
        $email = $this->collectEmail();
        $password = $this->collectPassword();
        $role = $this->collectRole();

        Contributor::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        info("Contributor [{$email}] created successfully.");

        return self::SUCCESS;
    }

    private function collectName(): string
    {
        return $this->option('name') ?? text(
            label: 'What is the contributor\'s name?',
            required: true,
        );
    }

    private function collectEmail(): string
    {
        return $this->option('email') ?? text(
            label: 'What is the contributor\'s email?',
            required: true,
            validate: function (string $value): ?string {
                if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return 'Please enter a valid email address.';
                }

                if (Contributor::query()->where('email', $value)->exists()) {
                    return 'A contributor with this email already exists.';
                }

                return null;
            },
        );
    }

    private function collectPassword(): string
    {
        if ($this->option('no-interaction')) {
            return 'password';
        }

        return password(
            label: 'What is the contributor\'s password?',
            required: true,
            validate: ['password' => ['required', Password::min(8)]],
        );
    }

    private function collectRole(): ContributorRole
    {
        $roleOption = $this->option('role');

        if ($roleOption !== null) {
            return ContributorRole::from($roleOption);
        }

        if ($this->option('no-interaction')) {
            return ContributorRole::Translator;
        }

        $selected = select(
            label: 'What role should the contributor have?',
            options: collect(ContributorRole::cases())
                ->mapWithKeys(fn (ContributorRole $role): array => [$role->value => $role->getLabel()])
                ->all(),
            default: ContributorRole::Translator->value,
        );

        return ContributorRole::from($selected);
    }
}
