<?php

namespace Kukulis\PermissionBased\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:user:add',
    description: 'Add user',
)]
class UserAddCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure() :void {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'User username')
            ->addArgument('name', InputArgument::OPTIONAL, 'User name');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $name = $input->getArgument('name');

        $existingUser = $this->userRepository->findOneByUsername($username);
        if ( $existingUser != null) {
            $io->error(sprintf('User [%s] already exists with id [%s]', $username, $existingUser->getId()));

            return Command::FAILURE;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setName($name);

        $this->userRepository->create($user);
        $this->entityManager->flush();

        $io->success(sprintf('User "%s" successfully added with id [%s].', $username, $user->getId()));

        return Command::SUCCESS;
    }
}
