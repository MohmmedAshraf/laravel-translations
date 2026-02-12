<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Http\Requests\StoreGroupRequest;
use Outhebox\Translations\Http\Requests\UpdateGroupRequest;
use Outhebox\Translations\Models\Group;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    public function index(Request $request): Response
    {
        $groups = QueryBuilder::for(Group::class)
            ->withCount('translationKeys')
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value): void {
                    $escaped = str_replace(['%', '_'], ['\%', '\_'], $value);
                    $query->where(function ($q) use ($escaped): void {
                        $q->where('name', 'like', "%{$escaped}%")
                            ->orWhere('namespace', 'like', "%{$escaped}%");
                    });
                }),
            ])
            ->orderBy('name')
            ->get();

        return Inertia::render('translations/groups/index', [
            'groups' => $groups,
            'filter' => $request->query('filter', []),
        ]);
    }

    public function store(StoreGroupRequest $request): RedirectResponse
    {
        Group::query()->create($request->validated());

        return redirect()->route('ltu.groups.index')
            ->with('success', 'Group created.');
    }

    public function update(UpdateGroupRequest $request, Group $group): RedirectResponse
    {
        $group->update($request->validated());

        return redirect()->route('ltu.groups.index')
            ->with('success', 'Group updated.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $name = $group->displayName();
        $group->delete();

        return redirect()->route('ltu.groups.index')
            ->with('success', "Group '{$name}' deleted.");
    }
}
