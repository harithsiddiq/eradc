<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Media extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'alt_text',
    ];

    public array $translatable = [
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function relations()
    {
        return $this->hasMany(MediaRelation::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'media_relations', 'media_id', 'post_id')
            ->withPivot(['relation_type', 'position', 'meta', 'created_at']);
    }

    public function featuredPosts()
    {
        return $this->hasMany(Post::class, 'featured_media_id');
    }
}
