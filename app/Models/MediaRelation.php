<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaRelation extends Model
{
    use HasFactory;

    protected $table = 'media_relations';

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'media_id',
        'relation_type',
        'position',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'meta' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}