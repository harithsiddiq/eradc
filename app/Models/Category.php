<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'link',
        'layout_style',
        'show_in_menu',
        'order',
        'parent_id',
        'description',
        'section_name',
    ];

    public array $translatable = [
        'name',
        'description',
        'section_name',
    ];

    protected function casts(): array
    {
        return [
            'show_in_menu' => 'boolean',
            'order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
