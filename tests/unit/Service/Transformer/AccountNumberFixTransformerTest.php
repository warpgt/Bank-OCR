<?php

declare(strict_types=1);

namespace AppTests\unit\Service\Transformer;

use App\Enum\AccountNumberCode;
use App\Model\AccountNumberDTO;
use App\Service\Transformer\AccountNumberFixTransformer;
use App\Service\Validator\AccountNumberValidator;
use PHPUnit\Framework\TestCase;

class AccountNumberFixTransformerTest extends TestCase
{
    private static AccountNumberFixTransformer $transformer;

    public static function setUpBeforeClass(): void
    {
        self::$transformer = new AccountNumberFixTransformer(new AccountNumberValidator());
    }

    public function testFixIllegal(): void
    {
        $dto = new AccountNumberDTO(['1', '1', '1', '1', '1', '1', '1', '1', '1'],AccountNumberCode::ILL);
        $expectedDto = new AccountNumberDTO(['7', '1', '1', '1', '1', '1', '1', '1', '1'],AccountNumberCode::OK);
        self::$transformer->transform($dto);
        $this->assertEquals($expectedDto, $dto);
    }

    public function testCantFixIllegal(): void
    {
        $dto = new AccountNumberDTO(['4', '4', '4', '4', '4', '4', '4', '4', '4'],AccountNumberCode::ILL);
        $expectedDto = new AccountNumberDTO(['4', '4', '4', '4', '4', '4', '4', '4', '4'],AccountNumberCode::ILL);
        self::$transformer->transform($dto);
        $this->assertEquals($expectedDto, $dto);
    }

    public function testAmbiguousKnownDigit(): void
    {
        $dto = new AccountNumberDTO(['4','9','0','0','6','7','7','1','5'],AccountNumberCode::ERR);
        $expectedDto = new AccountNumberDTO(['4','9','0','0','6','7','7','1','5'],AccountNumberCode::AMB);
        $expectedDto->setPossible([
            '490867715' => ['4','9','0','8','6','7','7','1','5'],
            '490067115' => ['4','9','0','0','6','7','1','1','5'],
            '490067719' => ['4','9','0','0','6','7','7','1','9']
        ]);
        self::$transformer->transform($dto);
        $this->assertEquals($expectedDto, $dto);
    }

    public function testValidUnknownDigit(): void
    {
        $dto = new AccountNumberDTO([['1','4'],'2','3','4','5','6','7','8','9'],AccountNumberCode::ILL);
        $expectedDto = new AccountNumberDTO(['1','2','3','4','5','6','7','8','9'],AccountNumberCode::OK);
        self::$transformer->transform($dto);
        $this->assertEquals($expectedDto, $dto);
    }
}