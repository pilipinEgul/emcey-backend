<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return GalleryImageResource::collection(
            GalleryImage::query()->orderBy('sort_order')->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $image = GalleryImage::create($this->validated($request, false));

        return (new GalleryImageResource($image))->response()->setStatusCode(201);
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $gallery->update($this->validated($request, true));

        return new GalleryImageResource($gallery);
    }

    public function destroy(GalleryImage $gallery)
    {
        $gallery->delete();

        return response()->json(['message' => 'Image deleted.']);
    }

    private function validated(Request $request, bool $partial): array
    {
        return $request->validate([
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'category' => ['nullable', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:180'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'image_path' => [$partial ? 'sometimes' : 'required', 'string', 'max:500'],
            'before_image_path' => ['nullable', 'string', 'max:500'],
            'after_image_path' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
