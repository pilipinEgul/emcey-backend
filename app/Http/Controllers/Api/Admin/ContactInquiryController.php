<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactInquiryResource;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    public function index()
    {
        return ContactInquiryResource::collection(
            ContactInquiry::query()->latest()->get()
        );
    }

    /** Count of messages not yet handled (anything not marked "closed"). */
    public function unread()
    {
        return response()->json([
            'data' => ['count' => ContactInquiry::query()->where('status', '!=', 'closed')->count()],
        ]);
    }

    public function update(Request $request, ContactInquiry $contactInquiry)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,closed'],
        ]);

        $contactInquiry->update($data);

        return new ContactInquiryResource($contactInquiry);
    }

    public function destroy(ContactInquiry $contactInquiry)
    {
        $contactInquiry->delete();

        return response()->json(['message' => 'Message deleted.']);
    }
}
