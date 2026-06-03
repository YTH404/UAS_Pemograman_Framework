<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    private const ROLE = 'student';

    protected $table = 'users';

    use SoftDeletes;

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
        static::addGlobalScope('student', function ($query) {
            $query->where('role', self::ROLE);
        });

        static::saving(function (self $student) {
            $student->role = self::ROLE;
        });

        static::deleting(function (self $student) {
            $student->studentClass()->delete();

            if ($student->isForceDeleting()) {
                return;
            }

            $baseCode = $student->getOriginal('username') ?? $student->username;
            $counter = static::withTrashed()
                ->where('username', 'like', $baseCode . '-deleted-%')
                ->count() + 1;

            $student->username = $baseCode . '-deleted-' . $counter;
            $student->saveQuietly();
        });
    }

    public function studentClass(): HasOne
    {
        return $this->hasOne(StudentClass::class, 'student_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
