<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title',
        'target_type',
        'category_id',
        'section_slug',
        'external_url',
        'order',
        'is_active',
    ];

    public array $translatable = [
        'title',
    ];

    protected $casts = [
        'title' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'resolved_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getResolvedUrlAttribute(): string
    {
        return match ($this->target_type) {
            'external' => $this->external_url ? url($this->external_url) : '#',
            'category' => $this->resolveCategoryUrl(),
            default => $this->resolveSectionUrl(),
        };
    }

    protected function resolveCategoryUrl(): string
    {
        if (! $this->category) {
            return route('home');
        }

        $anchor = $this->category->slug ?? null;
        if (! $anchor) {
            return route('home');
        }

        if ($this->category->layout_style === 'posts' || $anchor === 'posts') {
            return route('posts.index');
        }

        return route('home').'/#'.$anchor;
    }

    protected function resolveSectionUrl(): string
    {
        $slug = $this->section_slug;

        if (! $slug && $this->category) {
            $slug = $this->category->slug;
        }

        return $slug ? route('home').'/#'.ltrim($slug, '#') : route('home');
    }
}
