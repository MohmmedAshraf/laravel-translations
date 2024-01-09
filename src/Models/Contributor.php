<?php

namespace Outhebox\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Outhebox\LaravelTranslations\Enums\RoleEnum;

class Contributor extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $guarded = [];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'ltu_contributors';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => RoleEnum::class,
    ];

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'ltu_contributor_languages', 'contributor_id', 'language_id');
    }
}
