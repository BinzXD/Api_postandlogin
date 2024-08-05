<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'slug',
        'content', 
        'user_id',
        'category_id',
        'thumbnail',
        'published_at',
        'status',
        'meta_title',
        'meta_description',
    ];
    protected $casts = [
        'published_at' => 'datetime',
    ];
    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function categori(): BelongsTo
    {
        return $this->belongsTo(categorie::class, 'category_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
