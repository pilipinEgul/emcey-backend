<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        // Stored under storage/app/public/uploads and served via the
        // `public/storage` symlink at /storage/uploads/<file>.
        $path = $request->file('file')->store('uploads', 'public');

        return response()->json([
            'path' => '/storage/' . $path,
        ], 201);
    }
}
