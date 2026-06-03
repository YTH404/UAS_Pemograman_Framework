<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    private const ROLE = 'teacher';

    protected $table = 'users';

    use softDeletes;

    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    protected $attributes = [
        'role' => self::ROLE,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('teacher', function ($query) {
            $query->where('role', self::ROLE);
        });

        static::saving(function (self $teacher) {
            $teacher->role = self::ROLE;
        });

        static::deleting(function (self $class) {
            if ($class->isForceDeleting()) {
                return;
            }

            $baseCode = $class->getOriginal('username') ?? $class->username;
            $counter = static::withTrashed()
                ->where('username', 'like', $baseCode . '-deleted-%')
                ->count() + 1;

            $class->username = $baseCode . '-deleted-' . $counter;
            $class->saveQuietly();
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
