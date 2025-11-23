<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PostMeta extends Model
{
    use HasFactory, HasTranslations;   

    protected $table = 'post_meta';

    protected $fillable = [
        'post_id',
        'meta_key',
        'meta_value',
    ];

    public array $translatable = [
        'meta_value',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
