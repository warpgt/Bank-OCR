<?php

declare(strict_types=1);

namespace App\Service\Validator;

interface AccountNumberValidatorInterface
{
    public function validate(array $accountNumber): string;
}