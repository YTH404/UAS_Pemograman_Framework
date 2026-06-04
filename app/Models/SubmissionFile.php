<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SubmissionFile extends Model
{
    protected $fillable = [
        'submission_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function fileUrl(): string
    {
        return Storage::url($this->file_path);
    }
}
