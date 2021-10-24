<?php

declare(strict_types=1);

namespace App\Converter;

use App\Enum\InvalidDigit;

class AccountNumberConverter
{
    public static function toString(array $numbers): string
    {
        $printNumber = '';
        foreach ($numbers as $k => $number) {
            if (is_array($number)) {
                $printNumber .= InvalidDigit::CODE;
                continue;
            }
            $printNumber .= $number;
        }

        return $printNumber;
    }
}