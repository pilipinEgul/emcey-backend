<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        return AnnouncementResource::collection(
            Announcement::query()->active()->orderBy('sort_order')->latest()->get()
        );
    }
}
