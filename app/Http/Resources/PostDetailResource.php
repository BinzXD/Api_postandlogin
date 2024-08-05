<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
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
            'slug' => $this->slug,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
            'published_at' => Carbon::parse($this->published_at)->format('M d, Y'),
            'meta_description' => $this->meta_description,
            'penulis' => $this ->penulis,
            'categori' => $this ->categori,
            'penulis' => $this ->penulis,
            'tags' => $this ->tags
        ];
    }
}
