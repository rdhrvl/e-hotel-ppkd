<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
