<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->user_id;
        $posts = Post::when($userId !==null,function($query){
               return $query->where('user_id',$userId);
        })->latest()->with(['user','comments.replies','medias','likes'])->paginate(10);
        //,'comments.replies'
        //return $posts;
        return response()->json([
            "status" => true,
            "message" => "Post List",
            //"data" => $posts
            "data" =>['posts'=> PostResource::Collection($posts),'next_page_url'=> $posts->nextPageUrl()]
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
            'title' => ['required'],
            'description' => ['required'],
            'is_published' =>['in:0,1'],
            'media'=>['nullable','mimes:m4v,avi,flv,mp4,mov,jpeg,png,jpg,gif,svg','max:4000']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ]);
        }

        // $posts = Post::create($request_data);
        $post = Auth::user()->posts()->create($request->all());
        if($request->file('media')){
            $url = $request->file('media')->store('postMedia');
            $post->medias()->create([
               'name' => $request->file('media')->getClientOriginalName(),
               'type' => $request->file('media')->getMimeType(),
               'extension' => $request->file('media')->getClientOriginalExtension(),
               'url' => $url,
            ]);
        }
        // $post->title=$request->title;
        // $post->description=$request->description;
        // $post->user_id = auth()->user()->id;
        // $post->save();
        
        return response()->json([
            "status" => true,
            "message" => "Post created successfully.",
            //"data" => $post->only('id','title','description','is_published')
            "data" => new PostResource ($post)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $post=Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Post found.",
            "data" => new PostResource ($post)
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Post $post)
    public function updateMedia(Request $request, $id)
    {
        //
    }    
    
/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Post $post)
    public function update(Request $request, $id)
    {
        $request_data = $request->all();
        
        $validator = Validator::make($request_data, [
            'title' => 'required',
            'description' => 'required',
            'is_published' =>['in:0,1'],
            'media'=>['nullable','mimes:m4v,avi,flv,mp4,mov,jpeg,png,jpg,gif,svg','max:4000'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ]);      
        }
        $post=Post::find($id);
        $post->title = $request_data['title'];
        $post->description = $request_data['description'];
        $post->save();
        
        return response()->json([
            "status" => true,
            "message" => "Post updated successfully.",
            "data" => $post
        ]);
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json([
            "status" => true,
            "message" => "Post deleted successfully.",
            "data" => $post
        ]);
    }

}
