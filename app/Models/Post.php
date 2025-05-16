<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['user_id', 'title', 'content', 'image', 'visibility'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    protected static function boot()
    {
        parent::boot();


        static::deleting(function ($post) {

            // Ensure tags are detached when a post is deleted
            $post->tags()->detach();

            // Delete Image if it exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
        });
    }
}
