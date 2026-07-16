<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Closure;
use Illuminate\Http\Request;

class ClosureController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Closure::query()
                ->orderByRaw('weekday IS NULL') // recurring weekdays first
                ->orderBy('weekday')
                ->orderBy('date')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['nullable', 'date', 'required_without:weekday', 'unique:closures,date'],
            'weekday' => ['nullable', 'integer', 'between:1,7', 'required_without:date', 'unique:closures,weekday'],
            'reason' => ['nullable', 'string', 'max:160'],
        ]);

        // A closure is either a specific date OR a recurring weekday, never both.
        if (! empty($data['date'])) {
            $data['weekday'] = null;
        } else {
            $data['date'] = null;
        }

        $closure = Closure::create($data);

        return response()->json(['data' => $closure], 201);
    }

    public function destroy(Closure $closure)
    {
        $closure->delete();

        return response()->json(['message' => 'Closure removed.']);
    }
}
