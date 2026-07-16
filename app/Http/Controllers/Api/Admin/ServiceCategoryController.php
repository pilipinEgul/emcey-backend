<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        return ServiceCategoryResource::collection(
            ServiceCategory::query()->orderBy('sort_order')->orderBy('name')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, null);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $category = ServiceCategory::create($data);

        return (new ServiceCategoryResource($category))->response()->setStatusCode(201);
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $serviceCategory->update($this->validated($request, $serviceCategory->id));

        return new ServiceCategoryResource($serviceCategory);
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        return response()->json(['message' => 'Category deleted.']);
    }

    private function validated(Request $request, ?int $ignoreId): array
    {
        return $request->validate([
            'name' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('service_categories', 'slug')->ignore($ignoreId)],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);
    }
}
