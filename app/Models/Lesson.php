<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Lesson extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'duration_seconds' => 'integer',
        'order' => 'integer',
        'is_published' => 'boolean',
        'is_preview' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
