<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'client_title' => $this->client_title,
            'client_avatar' => $this->client_avatar,
            'quote' => $this->quote,
            'rating' => $this->rating,
            'source' => $this->source,
            'video_url' => $this->video_url,
            'is_featured' => $this->is_featured,
            'service' => $this->whenLoaded('service', fn () => [
                'id' => $this->service->id,
                'name' => $this->service->name,
                'slug' => $this->service->slug,
            ]),
        ];
    }
}
