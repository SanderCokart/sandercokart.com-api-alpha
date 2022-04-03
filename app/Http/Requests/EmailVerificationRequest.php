<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws ValidationException
     */
    public function authorize(): bool
    {
        $this->getValidatorInstance()->validate();
        $validatedData = $this->validated();
        $entry = DB::table('email_verifications')
                   ->where('identifier', $validatedData['identifier'])
                   ->where('token', $validatedData['token'])
                   ->first();
        dd($entry);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'identifier' => 'string|required',
            'token'      => 'string|required',
        ];
    }

    public function fulfill()
    {
        if (! $this->user()->hasVerifiedEmail()) {
            $this->user()->markEmailAsVerified();

            event(new Verified($this->user()));
        }
    }
}
