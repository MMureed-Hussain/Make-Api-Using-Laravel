<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\CommentResource;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'title'=> $this->title,
            'description'=> $this->description,
            'is_published'=> $this->is_published,
            'created_at'=> $this->created_at,
            'user' => new UserResource($this->user),
            'media' => MediaResource::collection($this->medias),
            'comments'=> CommentResource::collection($this->comments),
           //'like_on_post' =>LikePostResource::collection($this->like_on_post),
        ];  
    }
}
