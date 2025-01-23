<?php

namespace Kukulis\PermissionBased\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:user:list',
    description: 'Lists users',
)]
class UsersListCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $users = $this->userRepository->findAll();
        $rows =  array_map  ( fn($user)=>[$user->getUsername(), $user->getName(), $user->getPassword()], $users );
        $io->table(['Username', 'Name', 'Password'], $rows);

        return Command::SUCCESS;
    }
}
