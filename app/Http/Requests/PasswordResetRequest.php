<?php

namespace App\Http\Requests;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use App\Traits\HasTokenExpireableTokens;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetRequest extends FormRequest
{
    use HasTokenExpireableTokens;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password'   => [PasswordRule::defaults(), 'required', 'max:50', 'confirmed'],
            'identifier' => ['string', 'required'],
            'token'      => ['string', 'required'],
        ];
    }

    public function fulfill(): void
    {
        $validatedData = $this->validated();

        /** @var PasswordReset $passwordReset */
        $passwordReset = PasswordReset::where('identifier', $validatedData['identifier'])
                                      ->where('token', $validatedData['token'])
                                      ->first();

        if (! $passwordReset) abort(404, 'Invalid identifier and or token.');

        if ($this->tokenIsExpired($passwordReset)) {
            $passwordReset->delete();
            abort(401, 'Token has expired');
        }

        if ($passwordReset->delete()) {
            /** @var User $user */
            $user = User::find($passwordReset->user_id);

            $user->notify(new PasswordChangedNotification());

            $user->forceFill(['password' => bcrypt($validatedData['password'])])->save();
        }
    }
}
