<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];

    // Each post belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each post can have multiple tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Each post can have multiple comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Each post can be liked by multiple users
    // and a user can like multiple posts
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'post_user_views')
            ->withPivot('viewed_at')
            ->withTimestamps();
    }

    public function getLastViewedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
