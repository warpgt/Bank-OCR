<?php

declare(strict_types=1);

namespace App\Query\Message;

class FileReader
{
    private string $filePath;

    private array $numbers;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFile(): string
    {
        return $this->filePath;
    }

    public function setAccountNumbers(array $numbers): void
    {
        $this->numbers = $numbers;
    }

    public function getAccountNumbers(): array
    {
        return $this->numbers;
    }
}