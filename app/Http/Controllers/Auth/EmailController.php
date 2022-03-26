<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as PasswordRule;

class EmailController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emailCompromised(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email'    => 'string|email|unique:users,email',
            'password' => ['string', PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
            'user'     => 'integer|required',
            'token'    => 'string|required',
        ]);

        abort_if(!DB::table('email_changes')
            ->where('user_id', $validatedData['user'])
            ->where('token', $validatedData['token'])->delete(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'This user and token combination was not found in our systems.');

        $user = User::find($validatedData['user']);
        $user->update(['email' => $validatedData['email'], 'password' => bcrypt($validatedData['password'])]);

        DB::table('sessions')->where('user_id', $user['id'])->delete();

        return response()->json([
            'message' => 'Your email has been changed to ' . $validatedData['email'] . '.'
        ], JsonResponse::HTTP_OK);
    }

    public function emailChange(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'string|required|email|unique:users,email'
        ]);

        $request->user()->changeEmailAndNotify($validatedData['email']);

        return response()->json([
            'message' => 'Email changed successfully, please check your email and follow the link to re-verify.'
        ], JsonResponse::HTTP_OK);
    }
}
