<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ArticleResource extends JsonResource
{

    public static $wrap = false;

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
            'created_at' => $this->created_at,
            'publishedAt' => $this->published_at,
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
            'author' => $this->whenLoaded('author', function () {
                return new AuthorResource($this->author);
            }),
            'banner' => $this->whenLoaded('banner', function () {
                return new FileResource($this->banner);
            }),
        ];
    }
}
