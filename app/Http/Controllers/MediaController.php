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

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $image = $request->file('image');
        if($request->hasfile('image')){

        }
        else{
            return response()->json(['image is null']);
        }
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $media = Media::find($id);
        if (!$media) {
            return response()->json([
                'status' => false,
                'message' => 'media Not Found',
            ]);
        }
        $validate = Validator::make(
            $request->all(),
            [
                'title' => ['required'],
                'description' => ['required'],
                'is_published' =>['in:0,1'],
                'media'=>['nullable','mimes:m4v,avi,flv,mp4,mov,jpeg,png,jpg,gif,svg','max:4000'],
                'post_id'=> 'required'
            ],
        );
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Media Data',
                'errors' => $validate->errors()
            ], 401);
        }
        $media_path = public_path("storage/{$media->url}");
        $media->delete();
        unlink($media_path);
         $media->$url = $request->file('url')->store('postMedia', 'public');
        $media->post_id = $request->post_id;
        $media->save();
        return response()->json([
            'status' => true,
            'message' => 'Media Updated Successfully',
            'data' => $media
        ], 200);
    } 


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::find($id);
        $media->delete();
        unlink(public_path("storage/{$media->url}"));
        return response()->json([
            "status" => true,
            "message" => "Post deleted successfully.",
            "data" => $media
        ]);
    }
}
