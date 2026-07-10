<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Models\ContactInquiry;

class ContactInquiryController extends Controller
{
    public function store(StoreContactInquiryRequest $request)
    {
        $inquiry = ContactInquiry::create($request->validated() + ['status' => 'new']);

        return response()->json([
            'data' => [
                'id' => $inquiry->id,
                'message' => 'Thank you for reaching out — our team will get back to you shortly.',
            ],
        ], 201);
    }
}
