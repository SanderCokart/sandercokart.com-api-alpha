<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/** @mixin Article */
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
            'id'           => $this->id,
            'title'        => $this->title,
            'excerpt'      => $this->excerpt,
            'markdown'     => $this->when($request->routeIs('articles.show'), function () {
                return $this->markdown;
            }),
            'created_at'   => $this->created_at,
            'publishedAt'  => $this->published_at,
            'updated_at'   => $this->updated_at,
            'slug'         => $this->slug,
            'author'       => $this->whenLoaded('author', function () {
                return new AuthorResource($this->author);
            }),
            'banner'       => $this->whenLoaded('banner', function () {
                return new FileResource($this->banner);
            }),
            'article_type' => $this->whenLoaded('article_type', function () {
                return $this->articleType;
            }),
        ];
    }
}
