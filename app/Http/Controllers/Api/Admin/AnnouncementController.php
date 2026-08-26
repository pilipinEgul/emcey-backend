<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return AnnouncementResource::collection(
            Announcement::query()->orderBy('sort_order')->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $announcement = Announcement::create($this->validated($request, false));

        return (new AnnouncementResource($announcement))->response()->setStatusCode(201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $announcement->update($this->validated($request, true));

        return new AnnouncementResource($announcement);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted.']);
    }

    private function validated(Request $request, bool $partial): array
    {
        return $request->validate([
            'title' => [$partial ? 'sometimes' : 'required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:2000'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'tag' => ['nullable', 'string', 'max:40'],
            'link_url' => ['nullable', 'string', 'max:500'],
            'link_label' => ['nullable', 'string', 'max:80'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
