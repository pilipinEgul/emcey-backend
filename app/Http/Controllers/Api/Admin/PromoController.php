<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    public function index()
    {
        return PromoResource::collection(Promo::query()->latest()->get());
    }

    public function store(Request $request)
    {
        $promo = Promo::create($this->validated($request, null));

        return (new PromoResource($promo))->response()->setStatusCode(201);
    }

    public function update(Request $request, Promo $promo)
    {
        $promo->update($this->validated($request, $promo->id));

        return new PromoResource($promo);
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return response()->json(['message' => 'Promo deleted.']);
    }

    private function validated(Request $request, ?int $ignoreId): array
    {
        return $request->validate([
            'code' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:60', Rule::unique('promos', 'code')->ignore($ignoreId)],
            'title' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'in:percentage,fixed'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'minimum_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'string', 'max:500'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);
    }
}
