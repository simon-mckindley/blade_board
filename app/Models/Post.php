<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
