<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    protected $table = 'classes';   
    
    use SoftDeletes;
    
    protected $fillable = [
        'class_name',
        'class_code',
    ];

    protected static function booted()
    {
        static::deleting(function (self $class) {
            if ($class->isForceDeleting()) {
                return;
            }

            $baseCode = $class->getOriginal('class_code') ?? $class->class_code;
            $counter = static::withTrashed()
                ->where('class_code', 'like', $baseCode . '-deleted-%')
                ->count() + 1;

            $class->class_code = $baseCode . '-deleted-' . $counter;
            $class->saveQuietly();
        });
    }

    
}
