<?php

declare(strict_types=1);

namespace AppTests\unit\Service\Validator;

use App\Enum\AccountNumberCode;
use App\Service\Validator\AccountNumberValidator;
use PHPUnit\Framework\TestCase;

class AccountNumberValidatorTest extends TestCase
{
    private static AccountNumberValidator $validator;

    public static function setUpBeforeClass(): void
    {
        self::$validator = new AccountNumberValidator();
    }

    public function testValid(): void
    {
        $this->assertEquals(AccountNumberCode::OK, self::$validator->validate(['7', '1', '1', '1', '1', '1', '1', '1', '1']));
    }

    public function testIllegal(): void
    {
        $this->assertEquals(AccountNumberCode::ILL, self::$validator->validate([['6','9'], '2', '3', '4', '5', '6', '7', '8', '9']));
    }

    public function testError(): void
    {
        $this->assertEquals(AccountNumberCode::ERR, self::$validator->validate(['8', '8', '8', '8', '8', '8', '8', '8', '8']));
    }
}