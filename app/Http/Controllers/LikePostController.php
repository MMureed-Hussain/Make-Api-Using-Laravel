<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LikePostResource;

class LikePostController extends Controller
{
     
    public function existsValidation()
    {
        return view('validation.existsValidation');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Post::withCount('postlike')->get();
        return response()->json([
            "status" => true,
            "message" => "Post Likes List",
            "data" => $likes
            
            //"data" => LikePostResource :: Collection($likes)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = $request->all();
        
        $validator = Validator::make($request_data, [
            'user_id' =>['required'],
            'post_id' =>['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ]);
        }

        //$like = Auth::user()->post_likes()->create($request->all());
       // if ($post->likes->contains('user_id', auth()->id())
        if (!(PostLike::where('user_id', $request->user_id)->where('post_id', $request->post_id)->first()) ) 
        {
          $like = PostLike::create([
            'user_id'=>Auth::user()->id,
            'user_id'=>$request->user_id,
            'post_id'=>$request->post_id
          ]);
            return response()->json([
                "status" => true,
                "message" => "Like created successfully.",
               //"data" => $like
                // "data" => $comment->only('id','description','parent_id')
                "data" => new LikePostResource ($like)
            ]);
        }
        else
        {
         return response()->json([
            "status" => true,
            "message" => "User Already Exist.",
          ]);
        }
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request_data = $request->all();
        $post=Post::find($id);
        $validator = Validator::make($request_data, [
            'like_on_post' => 'required',
            'user_id' =>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ]);      
        }     
        $post->description = $request_data['description'];
        $post->user_id = $request_data['user_id'];
        $post->save();
    
        return response()->json([
            "status" => true,
            "message" => "Likes updated successfully.",
            "data" => $post
        ]);
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $like= PostLike::find($id);
        $comment->delete();
        return response()->json([
            "status" => true,
            "message" => "Like deleted successfully.",
            "data" =>$like
        ]);
    }
}
