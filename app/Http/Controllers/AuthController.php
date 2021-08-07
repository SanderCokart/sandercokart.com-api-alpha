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
        //validate login
        $validatedData = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
            'remember_me' => 'boolean|nullable',
        ]);

        $remember_me = $validatedData['remember_me'] ?? false;

        //attempt login otherwise return abort 401
        if (!auth()->attempt($validatedData, $remember_me)) {
            abort(401);
        }

        //return user
        return response()->json(['user' => auth()->user()]);

    }
}
