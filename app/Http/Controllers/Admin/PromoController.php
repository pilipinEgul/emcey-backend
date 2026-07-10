<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->paginate(20);

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.form', [
            'promo' => new Promo(['type' => 'percentage', 'is_active' => true]),
        ]);
    }

    public function store(Request $request)
    {
        Promo::create($this->validated($request));

        return redirect()->route('admin.promos.index')->with('status', 'Promo created.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.form', ['promo' => $promo]);
    }

    public function update(Request $request, Promo $promo)
    {
        $promo->update($this->validated($request, $promo->id));

        return redirect()->route('admin.promos.index')->with('status', 'Promo updated.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return back()->with('status', 'Promo deleted.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:promos,code,'.($id ?? 'NULL')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'string', 'max:1024'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
