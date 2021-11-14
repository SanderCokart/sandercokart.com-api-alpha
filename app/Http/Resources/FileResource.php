<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this['id'],
            'original_name' => $this['original_name'],
            'created_at' => $this['created_at'],
            'relative_url' => $this->when((boolean)$this['is_private'] === false, $this['relative_url'])
        ];
    }
}
