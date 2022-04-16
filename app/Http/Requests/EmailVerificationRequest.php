<?php

namespace App\Http\Requests;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'identifier' => 'string|required',
            'token'      => 'string|required',
        ];
    }

    public function fulfill()
    {
        $validatedData = $this->validated();

        /** @var EmailVerification $emailVerification */
        $emailVerification = EmailVerification::where('identifier', $validatedData['identifier'])
                                              ->where('token', $validatedData['token'])
                                              ->first();

        if (! $emailVerification) abort(404, 'Invalid identifier and or token.');

        if ($emailVerification && $this->tokenIsExpired($emailVerification)) {
            $emailVerification->delete();
            abort(401, 'Token has expired');
        }


        if (! $this->user()->hasVerifiedEmail()) {
            $this->user()->markEmailAsVerified();
        }
    }

    public function tokenIsExpired(EmailVerification $emailVerification): bool
    {
        return $emailVerification->expires_at < now();
    }
}
