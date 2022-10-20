<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LikePostResource;

class DislikeController extends Controller
{
        public function dislike(Post $post)
        {

            $attributes = [
                ['user_id', '=', auth()->user()->id],
                ['post_id', '=', $post->id]
            ];

            $like = PostLike::where($attributes);

            if($like->exists()) {
                $like->delete();
                return response()->json([
                    "status" => true,
                    "message" => "DisLike Menas Delete successfully.",
                   //"data" => $like
                    // "data" => $comment->only('id','description','parent_id')
                    // "data" => new LikePostResource ($like)
                ]);

            } 
            else
             {
                PostLike::create(['user_id' => auth()->user()->id, 'post_id' => $post->id]);

                return response()->json([
                    "status" => true,
                    "message" => "Like created successfully.",
                   "data" => PostLike::count(),
                   "data" => $post->session()->get('post_id')

                   
                  // "data" => $comment->only('id','description','parent_id')
                   //"data" => new LikePostResource ($like)
                ]);
            }

        }
}
