<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private const TOKEN_TTL_DAYS = 30;

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! $user->is_admin || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match an admin account.'],
            ]);
        }

        // Plaintext is returned once; only its hash is stored.
        $plain = Str::random(64);

        $user->adminTokens()->create([
            'name' => $request->userAgent(),
            'token' => hash('sha256', $plain),
            'expires_at' => now()->addDays(self::TOKEN_TTL_DAYS),
        ]);

        return response()->json([
            'token' => $plain,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $plain = $request->bearerToken();
        if ($plain) {
            AdminToken::where('token', hash('sha256', $plain))->delete();
        }

        return response()->json(['message' => 'Logged out.']);
    }
}
