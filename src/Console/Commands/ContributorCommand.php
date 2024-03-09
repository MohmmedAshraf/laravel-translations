<?php

namespace Outhebox\TranslationsUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class ContributorCommand extends Command
{
    protected $signature = 'translations:contributor
                    { name : Name of the contributor }
                    { email : Email associated with the contributor }
                    { role : The role to be assigned to the contributor (owner, translator, reviewer, translator_manager) }
                    { password : Password associated with the contributor }';

    protected $description = 'Create a new contributor for the Laravel Translations UI';

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (! $input->getArgument('name')) {
            $input->setArgument('name', text(
                label: 'Name of the contributor',
                placeholder: 'E.g. John Doe',
                required: 'The name of the contributor is required.',
            ));
        }

        if (! $input->getArgument('email')) {
            $input->setArgument('email', text(
                label: 'Email associated with the contributor',
                placeholder: 'E.g. example@domain.test',
                required: 'The email of the contributor is required.',
                validate: fn (string $value) => match (true) {
                    ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address must be valid.',
                    Contributor::where('email', $value)->count() > 0 => 'A contributor with this email already exists',
                    default => null
                },
                hint: 'The email address must be valid.',
            ));
        }

        if (! $input->getArgument('role')) {
            $input->setArgument('role', select(
                label: 'What role should the contributor have?',
                options: [
                    RoleEnum::owner->label() => RoleEnum::owner->label(),
                    RoleEnum::translator->label() => RoleEnum::translator->label(),
                ],
                default: RoleEnum::owner->label(),
                hint: 'The role may be changed at any time.'
            ));
        }

        if (! $input->getArgument('password')) {
            $input->setArgument('password', password(
                label: 'What is your password?',
                placeholder: 'E.g. password@$123',
                validate: fn (string $value) => match (true) {
                    strlen($value) < 8 => 'The password must be at least 8 characters.',
                    default => null
                },
                hint: 'Minimum 8 characters.'
            ));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');
        $password = $input->getArgument('password');

        $contributor = spin(
            fn () => Contributor::create([
                'name' => $name,
                'email' => $email,
                'role' => RoleEnum::fromLabel($role),
                'password' => Hash::make($password),
            ]),
            'Creating contributor...'
        );

        if (! $contributor) {
            $this->error('Contributor could not be created.');

            return CommandAlias::FAILURE;
        }

        table(['ID', 'Name', 'Email', 'Role', 'Password'], [
            [
                $contributor->id,
                $contributor->name,
                $contributor->email,
                $contributor->role->label(),
                'Hidden for security reasons.',
            ],
        ]);

        $this->info(PHP_EOL.'First things first, login at <info>'.route('ltu.login').'</info> and update your credentials.');

        return Command::SUCCESS;
    }
}
