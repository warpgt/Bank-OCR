<?php

declare(strict_types=1);

namespace App\Service\Transformer;

use App\Converter\AccountNumberConverter;
use App\Enum\AccountNumberCode;
use App\Enum\InvalidDigit;
use App\Model\AccountNumberDTO;
use App\Service\Validator\AccountNumberValidatorInterface;

use function array_shift;
use function count;
use function preg_match_all;
use function preg_quote;
use function sprintf;

class AccountNumberFixTransformer implements AccountNumberFixTransformerInterface
{
    private AccountNumberValidatorInterface $validator;

    public function __construct(AccountNumberValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform(AccountNumberDTO $accountNumberDTO): void
    {
        $possibleValidNumbers = [];
        $number = $accountNumberDTO->getNumber();

        $this->generate(0, $number, $possibleValidNumbers);

        if (0 === count($possibleValidNumbers)) {
            $accountNumberDTO->setCode(AccountNumberCode::ILL);
            return;
        }

        if (1 === count($possibleValidNumbers)) {
            $first = array_shift($possibleValidNumbers);
            $accountNumberDTO->setNumber($first);
            $accountNumberDTO->setCode(AccountNumberCode::OK);
            return;
        }

        if (1 < count($possibleValidNumbers)) {
            $accountNumberDTO->setPossible($possibleValidNumbers);
            $accountNumberDTO->setCode(AccountNumberCode::AMB);
        }
    }

    private function generate(int $position, array $number, array &$possibleValidNumbers): void
    {
        $originalNumber = $number;

        if (is_array($number[$position])) {
            $flat = AccountNumberConverter::toString($number);
            preg_match_all(sprintf('/%s/', preg_quote(InvalidDigit::CODE)), $flat, $matches);
            foreach ($number[$position] as $possibleDigit) {
                $number[$position] = $possibleDigit;

                if (!isset($matches[0]) || 2 > count($matches[0])) {
                    $this->addWhenValid($number, $possibleValidNumbers);
                }
            }
        }

        $this->checkPossibleDigit($position, $number, $possibleValidNumbers);

        if (8 > $position) {
            $this->generate(++$position, $originalNumber, $possibleValidNumbers);
        }
    }

    private function checkPossibleDigit(int $position, array $number, array &$possibleValidNumbers): void
    {
        switch ($number[$position]) {
            case 0:
                $number[$position] = 8;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 1:
                $number[$position] = 7;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 2:
                $number[$position] = 3;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 3:
                $number[$position] = 9;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 5:
                $number[$position] = 6;
                $this->addWhenValid($number, $possibleValidNumbers);
                $number[$position] = 9;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 6:
                $number[$position] = 5;
                $this->addWhenValid($number, $possibleValidNumbers);
                $number[$position] = 8;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 7:
                $number[$position] = 1;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 8:
                $number[$position] = 0;
                $this->addWhenValid($number, $possibleValidNumbers);
                $number[$position] = 6;
                $this->addWhenValid($number, $possibleValidNumbers);
                $number[$position] = 9;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
            case 9:
                $number[$position] = 3;
                $this->addWhenValid($number, $possibleValidNumbers);
                $number[$position] = 5;
                $this->addWhenValid($number, $possibleValidNumbers);
                $number[$position] = 8;
                $this->addWhenValid($number, $possibleValidNumbers);
                break;
        }
    }

    private function addWhenValid(array $number, array &$possibleValidNumbers): void
    {
        if (AccountNumberCode::OK === $this->validator->validate($number)) {
            $flat = AccountNumberConverter::toString($number);
            $possibleValidNumbers[$flat] = $number;
        }
    }
}