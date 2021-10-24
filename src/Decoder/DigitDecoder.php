<?php

declare(strict_types=1);

namespace App\Decoder;

use App\Enum\InvalidDigit;

use function strlen;
use function substr;

class DigitDecoder
{
    private static $digitPatterns = [
        '._.| |.|_|' => '0',
        '.|.|' => '1',
        '._._|.|_' => '2',
        '._._|._|' => '3',
        '.|_|.|' => '4',
        '._.|_._|' => '5',
        '._.|_.|_|' => '6',
        '._.|.|' => '7',
        '._.|_|.|_|' => '8',
        '._.|_|._|' => '9'
    ];

    public static function decode(string $digitPattern): string
    {
        return isset(self::$digitPatterns[$digitPattern]) ? (string)self::$digitPatterns[$digitPattern] : InvalidDigit::CODE;
    }

    public static function getPossibleDigits(string $digitPattern): array
    {
        $possibleDigits = [];

        $patternArray = str_split($digitPattern, 1);

        foreach (self::$digitPatterns as $pattern => $digit) {

            $patternCut = $pattern;
            $charsDifference = '';
            foreach ($patternArray as $char) {
                $position = strpos($patternCut, $char);
                if (false !== $position) {
                    if (0 < $position) {
                        $charsDifference .= substr($patternCut, 0, $position);
                    }
                    $patternCutTmp = substr($patternCut, $position + 1);
                    if (false !== $patternCutTmp) {
                        $patternCut = $patternCutTmp;
                    }
                } else {
                    $charsDifference .= $char;
                }
            }
            if (0 < strlen($patternCut)) {
                $charsDifference .= $patternCut;
            }

            $charsDifference = str_replace('.', '', $charsDifference);

            if (1 === strlen($charsDifference)) {
                $possibleDigits[] = $digit;
            }
        }
        return $possibleDigits;
    }
}