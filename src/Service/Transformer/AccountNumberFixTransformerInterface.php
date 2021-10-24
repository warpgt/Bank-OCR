<?php

declare(strict_types=1);

namespace App\Service\Transformer;

use App\Model\AccountNumberDTO;

interface AccountNumberFixTransformerInterface
{
    public function transform(AccountNumberDTO $accountNumberDTO): void;
}