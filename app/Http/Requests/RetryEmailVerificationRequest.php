<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RetryEmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !request()->user()->hasVerifiedEmail();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function fulfill()
    {
        request()->user()->sendEmailVerificationNotification();
    }
}
