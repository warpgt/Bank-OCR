<?php

declare(strict_types=1);

namespace AppTests\unit\Converter;

use App\Converter\AccountNumberConverter;
use PHPUnit\Framework\TestCase;

class AccountNumberConverterTest extends TestCase
{
    public function testOnlyDigitConverter(): void
    {
        $this->assertEquals('12?456789', AccountNumberConverter::toString([1, 2, [5, 7], 4, 5, 6, 7, 8, 9]));
    }

    public function testPossibleDigitConverter(): void
    {
        $this->assertEquals('123456789', AccountNumberConverter::toString([1, 2, 3, 4, 5, 6, 7, 8, 9]));
    }
}