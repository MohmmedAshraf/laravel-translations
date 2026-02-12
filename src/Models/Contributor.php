<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Outhebox\Translations\Contracts\TranslatableUser;
use Outhebox\Translations\Database\Factories\ContributorFactory;
use Outhebox\Translations\Enums\ContributorRole;

class Contributor extends Authenticatable implements TranslatableUser
{
    use HasFactory, HasUlids;

    protected $table = 'ltu_contributors';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'invite_token',
        'invite_expires_at',
        'last_active_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'invite_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => ContributorRole::class,
            'password' => 'hashed',
            'is_active' => 'boolean',
            'invite_expires_at' => 'datetime',
            'last_active_at' => 'datetime',
        ];
    }

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'ltu_contributor_language')
            ->withTimestamps();
    }

    public function getTranslationDisplayName(): string
    {
        return $this->name;
    }

    public function getTranslationRole(): string
    {
        return $this->role->value;
    }

    public function getTranslationEmail(): string
    {
        return $this->email;
    }

    public function getTranslationId(): string
    {
        return (string) $this->getKey();
    }

    public static function activeOwnerCount(): int
    {
        return static::query()
            ->where('role', ContributorRole::Owner)
            ->where('is_active', true)
            ->count();
    }

    public function isLastActiveOwner(): bool
    {
        return $this->role === ContributorRole::Owner
            && $this->is_active
            && static::activeOwnerCount() <= 1;
    }

    protected static function newFactory(): ContributorFactory
    {
        return ContributorFactory::new();
    }
}
