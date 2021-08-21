<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        /* validate the form */
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        /* encrypt password */
        $validatedData['password'] = bcrypt($validatedData['password']);

        /* create user */
        $user = User::create($validatedData);

        /* send user a verify email link */
        event(new Registered($user));

        return response()->json($user);
    }

    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'Email has been verified!',
        ], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'required|boolean',
        ]);

        $remember_me = $validatedData['remember_me'] ?? false;
        unset($validatedData['remember_me']);

        //attempt login otherwise return abort 401
        if (!auth()->attempt($validatedData, $remember_me)) {
            abort(401, 'Incorrect email or password!');
        }

        //return user
        return response()->json(['user' => auth()->user()]);
    }

    public function check(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}
