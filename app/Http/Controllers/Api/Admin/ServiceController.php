<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        return ServiceResource::collection(
            Service::query()->with('category')->orderBy('sort_order')->orderBy('name')->get()
        );
    }

    public function show(Service $service)
    {
        return new ServiceResource($service->load('category'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, null);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $service = Service::create($data);

        return (new ServiceResource($service->load('category')))->response()->setStatusCode(201);
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validated($request, $service->id);
        $service->update($data);

        return new ServiceResource($service->load('category'));
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['message' => 'Service deleted.']);
    }

    private function validated(Request $request, ?int $ignoreId): array
    {
        return $request->validate([
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('services', 'slug')->ignore($ignoreId)],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'sr_artist_first_session' => ['nullable', 'numeric', 'min:0'],
            'master_artist_first_session' => ['nullable', 'numeric', 'min:0'],
            'sr_artist_second_session' => ['nullable', 'numeric', 'min:0'],
            'master_artist_second_session' => ['nullable', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'cover_image' => ['nullable', 'string', 'max:500'],
            'gallery' => ['nullable', 'array'],
            'benefits' => ['nullable', 'array'],
            'process_steps' => ['nullable', 'array'],
            'aftercare' => ['nullable', 'array'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
