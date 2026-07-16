<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return FaqResource::collection(
            Faq::query()->orderBy('sort_order')->orderBy('id')->get()
        );
    }

    public function store(Request $request)
    {
        $faq = Faq::create($this->validated($request, false));

        return (new FaqResource($faq))->response()->setStatusCode(201);
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request, true));

        return new FaqResource($faq);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->json(['message' => 'FAQ deleted.']);
    }

    private function validated(Request $request, bool $partial): array
    {
        return $request->validate([
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'category' => ['nullable', 'string', 'max:120'],
            'question' => [$partial ? 'sometimes' : 'required', 'string', 'max:255'],
            'answer' => [$partial ? 'sometimes' : 'required', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);
    }
}
