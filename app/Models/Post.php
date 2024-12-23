<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'image', 'category_id', 'status', 'user_id'];



    public function category()
    {
        return $this->belongsTo(Category::class);
    }
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
public function getImageUrlAttribute()
{
    return $this->image ? asset('storage/' . $this->image) : null;
}
}

