<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use App\Rules\AssignableRole;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

         $id = $this->get('id') ?? request()->route('id');
        $rules= [
            'name' => 'required',
            'email' => 'required|unique:' . config('permission.model_has_permissions.users','users').',email,' .$id,
            'password' => ['required', 'string', 'min:6'],
            'roles' => ['required', new AssignableRole()],

        ];
        return $rules;
    }
    protected function prepareForValidation()
    {
        $password = $this->input('password');
        if (!empty($password) && strlen($password) >= 6) {
            $this->merge([
                'password' => Hash::make($password),
                'roles' => is_array($this->input('roles')) ? $this->input('roles') : [$this->input('roles')],
            ]);
        }
    }
    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}