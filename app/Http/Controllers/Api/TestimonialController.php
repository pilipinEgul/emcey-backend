<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query()
            ->where('is_published', true)
            ->with('service')
            ->orderBy('sort_order')
            ->latest();

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $perPage = min(48, max(1, $request->integer('per_page', 12)));

        return TestimonialResource::collection($query->paginate($perPage));
    }
}
