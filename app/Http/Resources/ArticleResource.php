<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'markdown' => $this->markdown,
            'createdAt' => $this->created_at,
            'publishedAt' => $this->published_at,
            'updatedAt' => $this->updated_at,
            'slug' => $this->slug,
            'author' => $this->whenLoaded('user', function () {
                return new AuthorResource($this->user);
            }),
            'status' => $this->whenLoaded('status', function () {
                return new StatusResource($this->status);
            }),
            'banner' => $this->whenLoaded('banner', function () {
                return new FileResource($this->banner);
            }),
        ];
    }
}
