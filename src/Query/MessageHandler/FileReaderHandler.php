<?php

declare(strict_types=1);

namespace App\Query\MessageHandler;

use App\Query\Message\FileReader;
use App\Reader\File;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use function file_exists;

class FileReaderHandler implements MessageHandlerInterface
{
    public function __invoke(FileReader $message)
    {
        if (false === file_exists($message->getFile())) {
            throw new InvalidArgumentException('File not exists');
        }

        $reader = new File($message->getFile());

        $message->setAccountNumbers($reader->getAccountNumbers());
    }
}