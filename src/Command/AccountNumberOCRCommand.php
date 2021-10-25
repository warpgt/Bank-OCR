<?php

declare(strict_types=1);

namespace App\Command;

use App\Converter\AccountNumberConverter;
use App\Enum\AccountNumberCode;
use App\Query\Message\FileReader;
use App\Model\AccountNumberDTO;
use App\Service\{
    Transformer\AccountNumberFixTransformerInterface,
    Validator\AccountNumberValidatorInterface
};
use InvalidArgumentException;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface
};
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

use function sprintf;

class AccountNumberOCRCommand extends Command
{
    protected static $defaultName = 'app:account-numbers-ocr';

    private MessageBusInterface $bus;

    private AccountNumberValidatorInterface $validator;

    private AccountNumberFixTransformerInterface $accountFixTransformer;

    public function __construct(
        MessageBusInterface $bus,
        AccountNumberValidatorInterface $validator,
        AccountNumberFixTransformerInterface $accountFixTransformer
    ) {
        parent::__construct();
        $this->bus = $bus;
        $this->validator = $validator;
        $this->accountFixTransformer = $accountFixTransformer;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filePath', InputArgument::REQUIRED, 'Account numbers file path')
            ->setHelp('Account numbers OCR');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $filePath = $input->getArgument('filePath');

            $msg = new FileReader($filePath);

            $this->bus->dispatch($msg);

            $this->userStoryFirst($msg, $output);

            $badAccountList = $this->userStorySecondThird($msg, $output);

            $this->userStoryFourth($badAccountList, $output);

        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

    private function userStoryFirst(FileReader $msg, OutputInterface $output): void
    {
        $output->writeln('User Story 1');
        foreach ($msg->getAccountNumbers() as $accountNumber) {
            $output->writeln(AccountNumberConverter::toString($accountNumber));
        }
    }

    private function userStorySecondThird(FileReader $msg, OutputInterface $output): array
    {
        $output->writeln('User Story 2 + 3');
        $badAccountList = [];
        foreach ($msg->getAccountNumbers() as $accountNumber) {

            $code = $this->validator->validate($accountNumber);
            $displayNumber = AccountNumberConverter::toString($accountNumber);
            if ($code !== AccountNumberCode::OK) {
                $badAccountList[$displayNumber] = new AccountNumberDTO($accountNumber, $code);
                $output->writeln(sprintf('%s %s', $displayNumber, $code));
                continue;
            }

            $output->writeln(sprintf('%s', $displayNumber));
        }
        return $badAccountList;
    }

    private function userStoryFourth(array $badAccountList, OutputInterface $output): void
    {
        $output->writeln('User Story 4');
        /**
         * @var AccountNumberDTO $accountDto
         */
        foreach ($badAccountList as $accountDto) {

            $this->accountFixTransformer->transform($accountDto);

            $displayNumber = AccountNumberConverter::toString($accountDto->getNumber());

            switch ($accountDto->getCode()) {
                case AccountNumberCode::AMB:
                    $displayArray = [];
                    foreach ($accountDto->getPossible() as $possible) {
                        $displayArray[] = AccountNumberConverter::toString($possible);
                    }
                    $output->writeln(sprintf('%s %s [%s]', $displayNumber, AccountNumberCode::AMB, implode(',', $displayArray)));
                    break;
                case AccountNumberCode::ILL:
                    $output->writeln(sprintf('%s %s', $displayNumber, $accountDto->getCode()));
                    break;
                case AccountNumberCode::OK:
                    $output->writeln(sprintf('%s', $displayNumber));
                    break;
                default:
                    throw new InvalidArgumentException('Invalid status code');
            }
        }
    }

}