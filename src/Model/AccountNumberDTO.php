<?php

declare(strict_types=1);

namespace App\Model;

class AccountNumberDTO
{
    private array $number;

    private string $code;

    private array $possible;

    public function __construct(array $number, string $code)
    {
        $this->number = $number;
        $this->code = $code;
    }

    public function getNumber(): array
    {
        return $this->number;
    }

    public function setNumber(array $number): AccountNumberDTO
    {
        $this->number = $number;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): AccountNumberDTO
    {
        $this->code = $code;
        return $this;
    }

    public function getPossible(): array
    {
        return $this->possible;
    }

    public function setPossible(array $possible): AccountNumberDTO
    {
        $this->possible = $possible;
        return $this;
    }
}