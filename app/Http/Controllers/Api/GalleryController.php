<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryImage::query()
            ->where('is_active', true)
            ->with('service')
            ->orderBy('sort_order')
            ->latest();

        if ($category = $request->string('category')->trim()->value()) {
            $query->where('category', $category);
        }

        if ($serviceSlug = $request->string('service')->trim()->value()) {
            $query->whereHas('service', fn ($q) => $q->where('slug', $serviceSlug));
        }

        $perPage = min(48, max(1, $request->integer('per_page', 24)));

        return GalleryImageResource::collection($query->paginate($perPage));
    }
}
