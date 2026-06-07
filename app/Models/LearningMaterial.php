<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LearningMaterial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'meeting',
        'title',
        'description',
        'material_type',
        'file_path',
        'external_link',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function doneMarks(): HasMany
    {
        return $this->hasMany(DoneMark::class, 'learning_material_id');
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function youtubeEmbedUrl(): ?string
    {
        if ($this->material_type !== 'video' || ! $this->external_link) {
            return null;
        }

        $parts = parse_url($this->external_link);
        $host = $parts['host'] ?? '';
        $path = trim($parts['path'] ?? '', '/');
        $videoId = null;

        if (str_contains($host, 'youtu.be')) {
            $videoId = explode('/', $path)[0] ?? null;
        }

        if (str_contains($host, 'youtube.com')) {
            if ($path === 'watch') {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? null;
            } elseif (str_starts_with($path, 'embed/')) {
                $videoId = str_replace('embed/', '', $path);
            } elseif (str_starts_with($path, 'shorts/')) {
                $videoId = str_replace('shorts/', '', $path);
            }
        }

        return $videoId ? 'https://www.youtube.com/embed/' . $videoId : null;
    }
}
