<?php

declare(strict_types=1);

namespace App\Reader;

use App\Decoder\DigitDecoder;
use App\Enum\InvalidDigit;
use RuntimeException;

use function array_map;
use function fgets;
use function fopen;
use function range;
use function str_split;
use function strlen;
use function trim;
use function sprintf;

class File implements ReaderInterface
{
    private string $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function getAccountNumbers(): array
    {
        $accounts = [];
        $accountNumber = 0;
        $handle = fopen($this->filePath, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (2 > strlen(trim($line))) {
                    $accountNumber++;
                    continue;
                }
                $accounts[$accountNumber][] = array_map('trim', str_split($line, 3));
            }
            fclose($handle);
        } else {
            throw new RuntimeException(sprintf('Error opening the file [%s]', $this->filePath));
        }

        $digit = [];
        $index = 0;
        foreach ($accounts as $number => $lines) {
            foreach (range(0, 8) as $section) {
                $numberPattern = $this->getNumberPattern($lines, $section);

                if (!isset($digit[$index])) {
                    $digit[$index] = [];
                }

                $digitFound = DigitDecoder::decode($numberPattern);
                if (InvalidDigit::CODE !== $digitFound) {
                    $digit[$index][] = $digitFound;
                    continue;
                }
                $digit[$index][] = DigitDecoder::getPossibleDigits($numberPattern);
            }
            $index++;
        }

        return $digit;
    }

    private function getNumberPattern($lines, $section): string
    {
        $flatPattern = '';
        if (!empty($lines[0][$section])) {
            $flatPattern .= '.' . trim($lines[0][$section]);
        }
        if (!empty($lines[1][$section])) {
            $flatPattern .= '.' . trim($lines[1][$section]);
        }
        if (!empty($lines[2][$section])) {
            $flatPattern .= '.' . trim($lines[2][$section]);
        }
        return $flatPattern;
    }
}