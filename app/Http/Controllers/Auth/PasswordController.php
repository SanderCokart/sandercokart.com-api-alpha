<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordCompromisedRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
    public function requestPassword(Request $request): string
    {
        $validatedData = $request->validate(['email' => 'required|email']);

        return Password::sendResetLink($validatedData);
    }

    public function passwordReset(Request $request): string
    {
        $validatedData = $request->validate([
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
            'email' => 'email|required',
            'token' => 'required',
        ]);

        return Password::reset(
            $validatedData,
            function ($user, $password) {
                $user->fill(['password' => bcrypt($password),])->save();
                event(new PasswordReset($user));
            }
        );
    }

    public function passwordCompromised(PasswordCompromisedRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = User::find($validatedData['user']);

        $user->forceFill(['password' => bcrypt($validatedData['password'])])->save();

        DB::table('sessions')->where('user_id', $user['id'])->delete();
        return response()->json(['message' => 'Password was reset successfully and you have been logged out of all devices.'], JsonResponse::HTTP_OK);
    }
}
