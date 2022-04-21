<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailCompromisedRequest;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\RetryEmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    public function emailCompromised(EmailCompromisedRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'Email changed successfully, please check your email and follow the link to re-verify.',
        ], JsonResponse::HTTP_OK);
    }

    public function emailChange(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'string|required|email|unique:users,email',
        ]);

        $request->user()->changeEmailAndNotify($validatedData['email']);

        return response()->json([
            'message' => 'Email changed successfully, please check your email and follow the link to re-verify.',
        ], JsonResponse::HTTP_OK);
    }

    public function emailVerify(EmailVerificationRequest $request): Response
    {
        $request->fulfill();

        return response()->noContent();
    }

    public function emailVerifyRetry(RetryEmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'A fresh verification link has been sent to your email address.',
        ], 200);
    }
}
