<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return ! $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255|min:2',
            'email'    => 'required|string||email|unique:users',
            'password' => ['required', 'string', 'max:50', PasswordRule::min(8)->symbols()->mixedCase()->numbers()],
        ];
    }

    public function fulfill()
    {
        $validatedData = $this->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        $user->sendEmailVerificationNotification();
    }

}
