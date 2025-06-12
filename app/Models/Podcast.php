<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;
    protected $fillable = [
        'channel_id',
        'title',
        'description',
        'audio_path',
        'cover_image',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }



    public function likes()
    {
        return $this->hasMany(PodcastLike::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'podcast_likes');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
