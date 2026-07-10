<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()
            ->where('is_active', true)
            ->with(['category'])
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($category = $request->string('category')->trim()->value()) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $perPage = min(48, max(1, $request->integer('per_page', 24)));

        return ServiceResource::collection($query->paginate($perPage));
    }

    public function show(string $slug)
    {
        $service = Service::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'testimonials' => fn ($q) => $q->where('is_published', true)->latest(),
                'galleryImages' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            ])
            ->firstOrFail();

        return new ServiceResource($service);
    }
}
