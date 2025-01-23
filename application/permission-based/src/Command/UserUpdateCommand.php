<?php

namespace Kukulis\PermissionBased\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AsCommand(
    name: 'permission:user:update',
    description: 'Update user',
)]
class UserUpdateCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private PasswordHasherFactoryInterface $hasherFactory,
    )
    {
        parent::__construct();
    }

    protected function configure() :void {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'User username')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED,'User password')
            ->addOption('name', 'N', InputOption::VALUE_REQUIRED,'User name');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');

        $user = $this->userRepository->findOneByUsername($username);
        if ( $user == null) {
            $io->error(sprintf('User [%s] does not exist', $username));

            return Command::FAILURE;
        }

        $password = $input->getOption('password');
        $wasChange = false;
        if ( $password !== null) {
            $hasher = $this->hasherFactory->getPasswordHasher(User::class);
            $hashedPassword = $hasher->hash($password);
            $user->setPassword($hashedPassword);
            $wasChange = true;
        }

        $name=$input->getOption('name');

        if ( $name !== null) {
            $user->setName($name);
            $wasChange = true;
        }

        if ( $wasChange) {
            $this->userRepository->create($user);
            $this->entityManager->flush();

            $io->success(sprintf('User "%s" successfully updated.', $username));
        } else {
            $io->note("Nothing to do");
        }

        return Command::SUCCESS;
    }
}
