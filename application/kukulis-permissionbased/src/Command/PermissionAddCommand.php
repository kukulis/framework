<?php

namespace Kukulis\PermissionBased\Command;

use Doctrine\ORM\EntityManagerInterface;
use Kukulis\PermissionBased\Entity\Permission;
use Kukulis\PermissionBased\Repository\PermissionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:add',
    description: 'Adds permission',
)]
class PermissionAddCommand extends Command
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
            ->addArgument('name', InputArgument::REQUIRED, 'Permission name');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        $existingPermission = $this->permissionRepository->findOneByName($name);

        if ($existingPermission != null) {
            $io->error(sprintf('Permission with the same name [%s] already exists with id [%s].', $name, $existingPermission->getId()));

            return Command::FAILURE;
        }

        $permission = new Permission();
        $permission->setName($name);
        $this->permissionRepository->create($permission);
        $this->entityManager->flush();

        $io->success(sprintf('permission %s added id=%s', $name, $permission->getId()));

        return Command::SUCCESS;
    }
}
