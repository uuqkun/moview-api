<?php

namespace App\Http\Resources;

use App\Models\Navlink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavlinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
        ];
    }
}
