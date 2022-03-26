<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordCompromisedRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
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

    public function passwordChange(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'string', 'max:50', 'confirmed'],
        ]);

        /** @var User $user */
        $user = $request->user();


        if (!Hash::check($validatedData['current_password'], $user->password)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'The current password didn\'t match the password in our system, please check if you entered the current password correctly');
        }

        $user->changePasswordAndNotify($validatedData['password']);

        $responses = [
            'Password changed successfully.',
            'Your password has been changed successfully.',
            'The process of changing your password was successful.',
            'The password has been changed successfully.',
            'Hey ' . $user->name . '! Your password has been changed successfully!',
        ];

        return response()->json([
            'message' => $responses[rand(0, sizeof($responses) - 1)],
        ], JsonResponse::HTTP_OK);
    }
}
