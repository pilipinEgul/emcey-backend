<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'service_category_id', 'name', 'slug', 'short_description', 'description',
    'price', 'promo_price',
    'sr_artist_first_session', 'master_artist_first_session',
    'sr_artist_second_session', 'master_artist_second_session',
    'duration_minutes', 'cover_image', 'gallery',
    'benefits', 'process_steps', 'aftercare',
    'meta_title', 'meta_description', 'meta_keywords',
    'is_featured', 'is_active', 'sort_order',
])]
class Service extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'promo_price' => 'decimal:2',
            'sr_artist_first_session' => 'decimal:2',
            'master_artist_first_session' => 'decimal:2',
            'sr_artist_second_session' => 'decimal:2',
            'master_artist_second_session' => 'decimal:2',
            'gallery' => 'array',
            'benefits' => 'array',
            'process_steps' => 'array',
            'aftercare' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
