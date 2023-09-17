<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneValidationRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function passes($attribute, $value)
    {
        // Your custom validation logic for the "phone" field here
        // Return true if the value is valid, false otherwise
        return strlen($value) === 12;
    }

    public function message()
    {
        return 'The phone number is not valid.'; // Customize the error message
    }
}
