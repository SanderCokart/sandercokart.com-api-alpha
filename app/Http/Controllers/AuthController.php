<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        return response()->json(['user' => new UserResource(auth()->user())]);
    }

    public function check(Request $request): JsonResponse|Response
    {
        if ($user = $request->user())
            return response()->json(['user' => new UserResource($user)]);
        else
            return response()->noContent();

    }

    public function logout(): void
    {
        auth()->guard('web')->logout();
    }
}
