<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NipRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(empty($value)) {
            return;
        }

        if(!self::NipValidator($value)){
            $fail(self::message());
        }
    }

    private static function NipValidator(mixed $value): bool
    {
        $nip = preg_replace('/[^0-9]+/', '', $value);

        if (10 !== strlen($nip)) {
            return false;
        }

        if ($nip === str_repeat('0', 10)) {
            return false;
        }

        if (preg_match('/^(.)\1*$/', $nip)) {
            return false;
        }

        $scales = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $total = 0;

        for ($i = 0; $i < 9; $i++) {
            $total += $scales[$i] * $nip[$i];
        }

        $checkDigit = $total % 11;
        
        if ($checkDigit == 10) {
            $checkDigit = 0;
        }

        return $checkDigit == $nip[9];
    }

    private static function message(): string
    {
        return __('validation.custom.nip_invalid');
    }
}
