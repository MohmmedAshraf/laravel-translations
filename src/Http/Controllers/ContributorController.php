<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Concerns\HasDataTable;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Enums\ContributorStatus;
use Outhebox\Translations\Http\Requests\StoreContributorRequest;
use Outhebox\Translations\Http\Requests\UpdateContributorRequest;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Services\TranslationAuth;
use Outhebox\Translations\Support\DataTable\Column;
use Outhebox\Translations\Support\DataTable\Filter;

class ContributorController extends Controller
{
    use HasDataTable;

    protected function tableModel(): string
    {
        return Contributor::class;
    }

    protected function tableColumns(): array
    {
        return [
            Column::make('name', 'Name')->searchable()->sortable()->fixed(),
            Column::make('email', 'Email')->searchable()->sortable(),
            Column::make('role', 'Role')->badge(ContributorRole::class)->sortable(),
            Column::make('languages_list', 'Languages')->fill(),
            Column::make('status', 'Status')->badge(ContributorStatus::class),
        ];
    }

    protected function tableFilters(): array
    {
        return [
            Filter::make('role', ContributorRole::class)->label('Role')->icon('shield'),
        ];
    }

    protected function tableFilterCallbacks(): array
    {
        return [
            'role' => fn (Builder $query, $value) => $query->where('role', $value),
        ];
    }

    protected function tableBaseQuery(): string|Builder
    {
        return Contributor::query()
            ->where('role', '!=', ContributorRole::Owner);
    }

    protected function modifyQuery(Builder $query): Builder
    {
        return $query->with('languages');
    }

    protected function tableDefaultSort(): string
    {
        return 'name';
    }

    protected function resourceName(): array
    {
        return ['contributor', 'contributors'];
    }

    public function index(Request $request): Response
    {
        $tableData = $this->paginatedTableData($request);

        $tableData['data'] = collect($tableData['data'])->map(function ($item) {
            $hasInviteToken = $item instanceof Contributor && ! empty($item->invite_token);
            $data = $item instanceof Contributor ? $item->toArray() : (array) $item;
            $data['languages_list'] = collect($data['languages'] ?? [])
                ->pluck('code')
                ->map(fn ($code) => strtoupper($code))
                ->implode(', ') ?: 'All';

            $data['status'] = match (true) {
                $hasInviteToken => 'invited',
                ! ($data['is_active'] ?? true) => 'inactive',
                default => 'active',
            };

            return $data;
        })->all();

        return Inertia::render('translations/contributors/index', [
            'data' => $tableData,
            'tableConfig' => $this->tableConfig($request),
            'languages' => Language::query()->active()->get(['id', 'code', 'name']),
            'roles' => collect(ContributorRole::cases())
                ->filter(fn (ContributorRole $role) => $role !== ContributorRole::Owner)
                ->values()
                ->map(fn (ContributorRole $role) => [
                    'value' => $role->value,
                    'label' => $role->getLabel(),
                ]),
        ]);
    }

    public function store(StoreContributorRequest $request): RedirectResponse
    {
        $auth = app(TranslationAuth::class);
        $validated = $request->validated();

        $role = ContributorRole::from($validated['role']);

        if ($role === ContributorRole::Owner && $auth->role() !== ContributorRole::Owner) {
            return redirect()->back()->withErrors(['role' => 'Only owners can assign the owner role.']);
        }

        $rawToken = Str::random(64);

        $contributor = Contributor::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => null,
            'role' => $role,
            'is_active' => true,
            'invite_token' => Hash::make($rawToken),
            'invite_expires_at' => now()->addDays(config('translations.invite.expires_days', 7)),
        ]);

        if (! empty($validated['language_ids'])) {
            $contributor->languages()->sync($validated['language_ids']);
        }

        return redirect()->back()->with('success', "Contributor '{$contributor->name}' invited.");
    }

    public function update(UpdateContributorRequest $request, Contributor $contributor): RedirectResponse
    {
        $auth = app(TranslationAuth::class);
        $validated = $request->validated();

        $newRole = ContributorRole::from($validated['role']);

        if ($newRole === ContributorRole::Owner && $auth->role() !== ContributorRole::Owner) {
            return redirect()->back()->withErrors(['role' => 'Only owners can assign the owner role.']);
        }

        if ($contributor->id === $auth->id() && $newRole->level() < $contributor->role->level()) {
            return redirect()->back()->withErrors(['role' => 'You cannot demote yourself.']);
        }

        if ($contributor->role === ContributorRole::Owner && $newRole !== ContributorRole::Owner && $contributor->isLastActiveOwner()) {
            return redirect()->back()->withErrors(['role' => 'Cannot change the last owner\'s role.']);
        }

        $contributor->update([
            'role' => $newRole,
            'is_active' => $validated['is_active'] ?? $contributor->is_active,
        ]);

        $contributor->languages()->sync($validated['language_ids'] ?? []);

        return redirect()->back()->with('success', "Contributor '{$contributor->name}' updated.");
    }

    public function toggleActive(Contributor $contributor): RedirectResponse
    {
        $auth = app(TranslationAuth::class);

        if ($contributor->id === $auth->id()) {
            return redirect()->back()->with('error', 'You cannot change your own status.');
        }

        if ($contributor->is_active && $contributor->isLastActiveOwner()) {
            return redirect()->back()->with('error', 'Cannot deactivate the last owner.');
        }

        $contributor->update(['is_active' => ! $contributor->is_active]);

        $action = $contributor->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Contributor '{$contributor->name}' {$action}.");
    }

    public function destroy(Contributor $contributor): RedirectResponse
    {
        $auth = app(TranslationAuth::class);

        if ($contributor->isLastActiveOwner()) {
            return redirect()->back()->with('error', 'Cannot deactivate the last owner.');
        }

        if ($contributor->id === $auth->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate yourself.');
        }

        $contributor->update(['is_active' => false]);

        return redirect()->back()->with('success', "Contributor '{$contributor->name}' deactivated.");
    }
}
