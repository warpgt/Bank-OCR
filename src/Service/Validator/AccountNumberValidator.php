<?php
declare(strict_types=1);

namespace App\Service\Validator;

use App\Enum\AccountNumberCode;

use function array_reverse;

class AccountNumberValidator implements AccountNumberValidatorInterface
{
    public function validate(array $accountNumber): string
    {
        foreach ($accountNumber as $digit) {
            if (is_array($digit)) {
                return AccountNumberCode::ILL;
            }
        }

        return (true === $this->isValidChecksum($accountNumber)) ? AccountNumberCode::OK : AccountNumberCode::ERR;
    }

    private function isValidChecksum(array $accountNumber): bool
    {
        $numbersArray = array_reverse($accountNumber);
        $checksum = 0;
        $multiply = 1;
        foreach ($numbersArray as $digit) {
            $checksum += $multiply * (int)$digit;
            $multiply++;
        }

        return $checksum % 11 === 0;
    }
}