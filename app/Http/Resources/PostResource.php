<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'markdown' => $this->markdown,
            'createdAt' => $this->created_at,
            'publishedAt' => $this->published_at,
            'updatedAt' => $this->updated_at,
            'slug' => $this->slug,
            'author' => new UserResource($this->whenLoaded('user')),
            'status' => $this->whenLoaded('status', function () {
                return $this->status->name;
            }),
            'banner' => new FileResource($this->whenLoaded('banner'))
        ];
    }
}
