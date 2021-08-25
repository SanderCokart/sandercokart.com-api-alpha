<?php

namespace App\Http\Requests;

use App\Events\Unverified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmailChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!hash_equals((string)$this->route('id'),
            (string)$this->user()->getKey())) {
            return false;
        }

        if (!hash_equals((string)$this->route('hash'),
            sha1($this->user()->getEmailForChange()))) {
            return false;
        }

        $token = DB::table('email_change_requests')
            ->where('id', $this->user()->getKey())
            ->where('token', (string)$this->route('token'))
            ->first();

        if (!Hash::check((string)$this->route('token'), $token)) {
            return false;
        }

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
            'id' => 'exists:users,id',
            'token' => 'string',
            'hash' => 'string',
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        $this->user()->markEmailAsUnverified();

        event(new Unverified($this->user()));
    }
}
