<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'service_category_id' => $this->service_category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'promo_price' => $this->promo_price,
            'sr_artist_first_session' => $this->sr_artist_first_session,
            'master_artist_first_session' => $this->master_artist_first_session,
            'sr_artist_second_session' => $this->sr_artist_second_session,
            'master_artist_second_session' => $this->master_artist_second_session,
            'duration_minutes' => $this->duration_minutes,
            'cover_image' => $this->cover_image,
            'gallery' => $this->gallery,
            'benefits' => $this->benefits,
            'process_steps' => $this->process_steps,
            'aftercare' => $this->aftercare,
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'category' => new ServiceCategoryResource($this->whenLoaded('category')),
            'faqs' => FaqResource::collection($this->whenLoaded('faqs')),
            'testimonials' => TestimonialResource::collection($this->whenLoaded('testimonials')),
            'gallery_images' => GalleryImageResource::collection($this->whenLoaded('galleryImages')),
        ];
    }
}
