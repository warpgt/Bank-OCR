<?php

declare(strict_types=1);

namespace AppTests\unit\Decoder;

use App\Decoder\DigitDecoder;
use App\Enum\InvalidDigit;
use PHPUnit\Framework\TestCase;

class DigitDecoderTest extends TestCase
{
    /**
     * @param string $digitPattern
     * @param string $expectedDigit
     *
     * @dataProvider decodeDataProvider
     */
    public function testDecode(string $digitPattern, string $expectedDigit): void
    {
        $this->assertEquals($expectedDigit, DigitDecoder::decode($digitPattern));
    }

    /**
     * @param string $invalidDigitPattern
     * @param array $expectedDigits
     *
     * @dataProvider possibleDigitDataProvider
     */
    public function testPossibleDigitConverter(string $invalidDigitPattern, array $expectedDigits): void
    {
        $this->assertEquals($expectedDigits, DigitDecoder::getPossibleDigits($invalidDigitPattern));
    }

    public function decodeDataProvider(): \Generator
    {
        yield ['._.| |.|_|', '0'];
        yield ['.|.|', '1'];
        yield ['._._|.|_', '2'];
        yield ['._._|._|', '3'];
        yield ['.|_|.|', '4'];
        yield ['._.|_._|', '5'];
        yield ['._.|_.|_|', '6'];
        yield ['._.|.|', '7'];
        yield ['._.|_|.|_|', '8'];
        yield ['._.|_|._|', '9'];
        yield ['._||||._|', InvalidDigit::CODE];
    }

    public function possibleDigitDataProvider(): \Generator
    {
        yield ['._|.|', [1,4]];
        yield ['.| |.|_|', [0]];
        yield ['._._._|', [3,5]];
    }
}