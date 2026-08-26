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

    public function storeVideo(Request $request)
    {
        $request->validate([
            // Max 50 MB — also bounded by php.ini upload_max_filesize / post_max_size.
            'file' => ['required', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        return response()->json([
            'path' => '/storage/' . $path,
        ], 201);
    }
}
