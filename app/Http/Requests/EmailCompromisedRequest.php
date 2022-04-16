<?php

namespace App\Http\Requests;

use App\Models\EmailCompromised;
use App\Models\User;
use App\Notifications\EmailChangedNotification;
use App\Notifications\PasswordChangedNotification;
use App\Traits\HasTokenExpireableTokens;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as PasswordRule;

class EmailCompromisedRequest extends FormRequest
{
    use HasTokenExpireableTokens;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'      => ['string', 'email', 'unique:users,email'],
            'password'   => ['string', PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed'],
            'identifier' => ['string', 'required'],
            'token'      => ['string', 'required'],
        ];
    }

    public function fulfill()
    {
        $validatedData = $this->validated();
        $compromisedEmail = EmailCompromised::where('identifier', $validatedData['identifier'])
                                            ->where('token', $validatedData['token'])
                                            ->first();

        if (! $compromisedEmail) abort(404, 'Invalid identifier and or token.');
        if ($this->tokenIsExpired($compromisedEmail)) {
            $compromisedEmail->delete();
            abort(401, 'Token has expired.');
        }

        if ($compromisedEmail->delete()) {
            /** @var User $user */
            $user = User::find($compromisedEmail->user_id);

            $user->notify(new EmailChangedNotification());

            $user->forceFill([
                'email'    => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ])->save();

            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

    }
}
