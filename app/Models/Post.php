<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasFactory;
    use HasTranslations;


    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'status',
        'author_id',
        'featured_image_path',
        'category_id',
        'additional_images',
        'published_at',
    ];

    public array $translatable = [
        'title',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'additional_images' => 'array',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Featured image is stored as path in the posts table now.

    public function meta()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function mediaRelations()
    {
        return $this->hasMany(MediaRelation::class);
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_relations', 'post_id', 'media_id')
            ->withPivot(['relation_type', 'position', 'meta', 'created_at']);
    }

}
