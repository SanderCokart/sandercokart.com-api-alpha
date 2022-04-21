<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordCompromisedRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
    public function passwordForgot(ForgotPasswordRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'If a user with that email address exists, you will receive an email with instructions on how to reset your password.',
        ], JsonResponse::HTTP_OK);
    }

    public function passwordReset(PasswordResetRequest $request): JsonResponse
    {
        $request->fulfill();

        /*SEND SUCCESSFUL PASSWORD RESET COMPROMISED EMAIL*/

        return response()->json([
            'message' => 'Password reset successfully.',
        ], Response::HTTP_OK);
    }

    public function passwordCompromised(PasswordCompromisedRequest $request): JsonResponse
    {
        $request->fulfill();
        return response()->json(['message' => 'Password was reset successfully and you have been logged out of all devices.'], JsonResponse::HTTP_OK);
    }

    public function passwordChange(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => [PasswordRule::defaults(), 'required', 'string', 'max:50', 'confirmed'],
        ]);

        /** @var User $user */
        $user = $request->user();


        if (! Hash::check($validatedData['current_password'], $user->password)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'This is not your current password.');
        }

        $user->changePasswordAndNotify($validatedData['password']);

        return response()->json([
            'message' => 'Password changed successfully.',
        ], JsonResponse::HTTP_OK);
    }
}
