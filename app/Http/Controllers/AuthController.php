<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'required|boolean',
        ]);

        $remember_me = $validatedData['remember_me'] ?? false;
        unset($validatedData['remember_me']);

        if (!auth()->attempt($validatedData, $remember_me)) {
            abort(401, 'Incorrect email or password!');
        }

        return response()->json(['user' => auth()->user(), 'id' => session()->getId()]);
    }

    public function check(): JsonResponse
    {
        return response()->json(['user' => auth()->user()]);
    }

    public function logout(): void
    {
        auth()->guard('web')->logout();
    }
}
