<?php

namespace App\Http\Requests;

use App\Models\CompromisedPassword;
use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use App\Traits\HasTokenExpireableTokens;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordCompromisedRequest extends FormRequest
{
    use HasTokenExpireableTokens;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => [PasswordRule::defaults(), 'required', 'max:50', 'confirmed'],
            'token'    => 'required|string',
            'identifier'    => 'required|string',
        ];
    }

    public function fulfill()
    {
        $validatedData = $this->validated();
        $compromisedPassword = CompromisedPassword::where('token', $validatedData['token'])
                                                  ->where('identifier', $validatedData['identifier'])
                                                  ->first();

        if (! $compromisedPassword) abort(404, 'Invalid identifier and or token.');
        if ($this->tokenIsExpired($compromisedPassword)) {
            $compromisedPassword->delete();
            abort(401, 'Token has expired.');
        }

        if ($compromisedPassword->delete()) {
            /** @var User $user */
            $user = User::find($compromisedPassword->user_id);

            $user->notify(new PasswordChangedNotification());

            $user->forceFill(['password' => bcrypt($validatedData['password'])])->save();

            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

    }
}
