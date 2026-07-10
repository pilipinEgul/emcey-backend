<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order');

        if ($serviceId = $request->integer('service_id')) {
            $query->where('service_id', $serviceId);
        } elseif ($request->has('general')) {
            $query->whereNull('service_id');
        }

        return FaqResource::collection($query->get());
    }
}
