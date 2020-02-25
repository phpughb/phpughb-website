<?php

namespace App\Command;

use App\Domain\User\UserCreateTokenManager;
use App\Dto\AddUserDto;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserCreateCommand extends Command
{
    private ValidatorInterface $validator;
    private UserCreateTokenManager $userCreateTokenSender;

    public function __construct(ValidatorInterface $validator, UserCreateTokenManager $userCreateTokenSender)
    {
        parent::__construct('app:user:create');
        $this->validator = $validator;
        $this->userCreateTokenSender = $userCreateTokenSender;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Sendet eine Aktivierungs-Email zur angegebenen Adresse')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addOption('isAdmin', null, InputOption::VALUE_NONE, 'isAdmin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $isAdmin = (bool) $input->getOption('isAdmin');

        $addUserDto = new AddUserDto($email, $isAdmin);

        $violationList = $this->validator->validate($addUserDto);
        if (\count($violationList) > 0) {
            /** @var ConstraintViolationInterface $violation */
            foreach ($violationList as $violation) {
                $io->error(
                    sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage())
                );
            }

            return 0;
        }

        $this->userCreateTokenSender->send($addUserDto->email, $addUserDto->isAdmin);
        $io->success(
            sprintf('Aktivierungs-Email an %s gesendet', $addUserDto->email)
        );

        return 0;
    }
}
