<?php

namespace App\Http\Resources;

use App\Models\ArticleBanner;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @mixin ArticleBanner
 */
class ArticleBannerResource extends JsonResource
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
            'id'           => $this->id,
            'relative_url' => $this->relative_url,
            'is_private'   => $this->is_private,
        ];
    }
}
