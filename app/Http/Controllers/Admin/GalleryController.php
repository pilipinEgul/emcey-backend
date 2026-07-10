<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Service;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::with('service')->orderBy('sort_order')->latest()->paginate(24);

        return view('admin.gallery.index', compact('images'));
    }

    public function create()
    {
        return view('admin.gallery.form', [
            'image' => new GalleryImage(['is_active' => true]),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        GalleryImage::create($this->validated($request));

        return redirect()->route('admin.gallery.index')->with('status', 'Image added.');
    }

    public function edit(GalleryImage $gallery)
    {
        return view('admin.gallery.form', [
            'image' => $gallery,
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $gallery->update($this->validated($request));

        return redirect()->route('admin.gallery.index')->with('status', 'Image updated.');
    }

    public function destroy(GalleryImage $gallery)
    {
        $gallery->delete();

        return back()->with('status', 'Image deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'service_id' => ['nullable', 'exists:services,id'],
            'category' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'image_path' => ['required', 'string', 'max:1024'],
            'before_image_path' => ['nullable', 'string', 'max:1024'],
            'after_image_path' => ['nullable', 'string', 'max:1024'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
