<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::paginate(05);
        return response()->json([
            "status" => true,
            "message" => "Comment List",
            //"data" => $comments
            "data" => CommentResource :: Collection($comments)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'description' => ['required'],
            'post_id' =>['required_without:parent_id','exists:posts,id'],
            'parent_id' =>['required_without:post_id','exists:comments,id'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ]);
        }
        if($request->parent_id){
          $comment = Comment::create([
            'description' => $request->description,
            'user_id'=>Auth::user()->id,
            'post_id'=>$request->post_id,
            'parent_id'=>$request->parent_id
          ]);
        }else{
            $post = Post::find($request->post_id);
            $comment = $post->comments()->create([
                'description' =>$request->description,
                'user_id'=> Auth::user()->id,
            ]);

        }

        return response()->json([
            "status" => true,
            "message" => "Comment created successfully.",
            //"data" => $comment->only('id','description','parent_id')
            "data" => new CommentResource ($comment)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request , $id)
    {
        $commnet=Comment::find($id);
        if (!$commnet) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found'
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Comment found.",
            "data" => $commnet
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
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
        $comment=Comment::find($id);
        $validator = Validator::make($request_data, [
            'description' => 'required',
            'post_id' =>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ]);      
        }     
        $comment->description = $request_data['description'];
        $comments->post_id = $request_data['post_id'];
        $comment->save();
    
        return response()->json([
            "status" => true,
            "message" => "Comment updated successfully.",
            "data" => $comment
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
        $comment= Comment::find($id);
        $comment->delete();
        return response()->json([
            "status" => true,
            "message" => "Comment deleted successfully.",
            "data" =>$comment
        ]);
    }
}
