<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\PostLike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'is_published',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function medias(){
        return $this->hasMany(Media::class);
    }
    public function postlike(){
        return $this->hasMany(PostLike::class);
    }
}
