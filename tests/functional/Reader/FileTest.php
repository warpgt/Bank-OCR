<?php

declare(strict_types=1);

namespace AppTests\functional\Reader;

use App\Reader\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testReadAccountNumbers(): void
    {
        $fileReader = new File('tests/functional/data/account_numbers.txt');
        $this->assertEquals(
            [
                ['0', '0', '0', '0', '0', '0', '0', '0', '0'],
                ['1', '1', '1', '1', '1', '1', '1', '1', '1'],
                ['2', '2', '2', '2', '2', '2', '2', '2', '2'],
                ['3', '3', '3', '3', '3', '3', '3', '3', '3'],
                ['4', '4', '4', '4', '4', '4', '4', '4', '4'],
                ['5', '5', '5', '5', '5', '5', '5', '5', '5'],
                ['6', '6', '6', '6', '6', '6', '6', '6', '6'],
                ['7', '7', '7', '7', '7', '7', '7', '7', '7'],
                ['8', '8', '8', '8', '8', '8', '8', '8', '8'],
                ['9', '9', '9', '9', '9', '9', '9', '9', '9']
            ],
            $fileReader->getAccountNumbers()
        );
    }
}