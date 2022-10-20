<?php

namespace App\Models;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'user_id',
        'post_id',
        'description',
    ];
    public function post(){
        return $this->belongsTo(Post::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function replies(){
        return $this->hasMany(Comment::class,'parent_id','id');
    }
}
