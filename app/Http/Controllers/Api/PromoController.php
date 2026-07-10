<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query()->active()->latest();

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        return PromoResource::collection($query->get());
    }
}
