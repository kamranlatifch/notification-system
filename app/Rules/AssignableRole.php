<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Spatie\Permission\Models\Role;
class AssignableRole implements Rule
{
    public function passes($attribute, $value)
    {

        return Role::where('id', $value)->where('assignable', true)->exists();
    }

    public function message()
    {
        return 'Please select a valid and assignable role.';
    }

}