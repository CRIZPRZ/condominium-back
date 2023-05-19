<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'phone' => 'required',
            'password' => $this->getPasswordRules(),
        ];
    }

    private function getPasswordRules()
    {
        return $this->updatePassword ? 'required|min:8' : '';
    }
}
