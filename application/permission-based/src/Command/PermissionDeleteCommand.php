<?php

namespace Kukulis\PermissionBased\Command;

use Doctrine\ORM\EntityManagerInterface;
use Kukulis\PermissionBased\Repository\PermissionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:delete',
    description: 'Deletes permission',
)]
class PermissionDeleteCommand extends Command
{
    public function __construct(
        private PermissionRepository   $permissionRepository,
        private EntityManagerInterface $entityManager

    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Permission name to delete');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');


        $permission = $this->permissionRepository->findOneByName($name);

        if ($permission == null) {
            $io->error(sprintf('Permission %s not found', $name));
            return Command::FAILURE;
        }

        $this->permissionRepository->delete($permission);

        $this->entityManager->flush();

        $io->success(sprintf('Permission %s has been deleted', $name));

        return Command::SUCCESS;
    }
}
