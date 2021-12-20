<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static RoleFactory factory(...$parameters)
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @mixin Eloquent
 */
class Role extends Model
{
    use HasFactory;

    const GUEST = 1;
    const ADMIN = 2;
    const VERIFIED = 3;

    public $timestamps = false;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
