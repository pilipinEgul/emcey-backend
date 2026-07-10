<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'title' => $this->title,
            'alt_text' => $this->alt_text,
            'image_path' => $this->image_path,
            'before_image_path' => $this->before_image_path,
            'after_image_path' => $this->after_image_path,
            'is_featured' => $this->is_featured,
            'service' => $this->whenLoaded('service', fn () => [
                'id' => $this->service->id,
                'name' => $this->service->name,
                'slug' => $this->service->slug,
            ]),
        ];
    }
}
