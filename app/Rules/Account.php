<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Account implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match(config('preg.phone_number'), $value) || filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '账号必须是手机号码或 Email 地址';
    }
}
