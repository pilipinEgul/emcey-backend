<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $services = Service::with('category')
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.services.index', compact('services', 'q'));
    }

    public function create()
    {
        return view('admin.services.form', [
            'service' => new Service(['is_active' => true, 'is_featured' => false, 'sort_order' => 0]),
            'categories' => ServiceCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Service::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('status', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', [
            'service' => $service,
            'categories' => ServiceCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validated($request, $service->id));

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('status', 'Service deleted.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug,'.($id ?? 'NULL')],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'sr_artist_first_session' => ['nullable', 'numeric', 'min:0'],
            'master_artist_first_session' => ['nullable', 'numeric', 'min:0'],
            'sr_artist_second_session' => ['nullable', 'numeric', 'min:0'],
            'master_artist_second_session' => ['nullable', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'string', 'max:1024'],
            'benefits' => ['nullable', 'string'],
            'process_steps' => ['nullable', 'string'],
            'aftercare' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        foreach (['benefits', 'process_steps', 'aftercare'] as $field) {
            $data[$field] = $this->linesToArray($data[$field] ?? null);
        }

        return $data;
    }

    private function linesToArray(?string $text): ?array
    {
        if (! $text) {
            return null;
        }

        $items = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $text))));

        return $items ?: null;
    }
}
