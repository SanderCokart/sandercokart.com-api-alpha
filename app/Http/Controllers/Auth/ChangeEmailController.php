<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChangeEmailController extends Controller
{
    public function __invoke(Request $request, User $user): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email', 'unique:users,email']
        ]);

        $user->changeEmailAndNotify($validatedData['email']);

        return response()->json(['message' => 'Email changed successfully, please check your email and follow the link to re-verify.'], JsonResponse::HTTP_OK);
    }
}
