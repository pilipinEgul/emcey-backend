<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return TestimonialResource::collection(
            Testimonial::query()->with('service')->orderBy('sort_order')->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $testimonial = Testimonial::create($this->validated($request, false));

        return (new TestimonialResource($testimonial->load('service')))->response()->setStatusCode(201);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($this->validated($request, true));

        return new TestimonialResource($testimonial->load('service'));
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted.']);
    }

    private function validated(Request $request, bool $partial): array
    {
        return $request->validate([
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'client_name' => [$partial ? 'sometimes' : 'required', 'string', 'max:120'],
            'client_title' => ['nullable', 'string', 'max:120'],
            'client_avatar' => ['nullable', 'string', 'max:500'],
            'quote' => [$partial ? 'sometimes' : 'required', 'string', 'max:1000'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'source' => ['nullable', 'string', 'max:60'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
