<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordCompromisedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !!DB::table('password_changes')
            ->where('user_id', (int)$this->route('user')->getKey())
            ->where('token', (string)$this->route('token'))
            ->delete();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => [PasswordRule::min(8)->symbols()->mixedCase()->numbers(), 'required', 'max:50', 'confirmed']
        ];
    }
}
