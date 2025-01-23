<?php

namespace Kukulis\PermissionBased\Command;

use Doctrine\ORM\EntityManagerInterface;
use Kukulis\PermissionBased\Repository\PermissionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:update',
    description: 'Modifies the permission',
)]
class PermissionUpdateCommand extends Command
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
            ->addArgument('name', InputArgument::REQUIRED, 'Permission name to update')
            ->addOption('action', 'a', InputOption::VALUE_REQUIRED, 'New action name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        $permission = $this->permissionRepository->findOneByName($name);

        if ($permission === null) {
            $io->error(sprintf('Permission with name "%s" not found', $name));
            return Command::FAILURE;
        }

        $action = $input->getOption('action');

        $wasChange = false;

        if ($action != null) {
            $permission->setAction($action);
            $wasChange = true;
        }

        if ($wasChange) {
            $this->permissionRepository->update($permission);
            $this->entityManager->flush();

            $io->success(sprintf('Permission "%s" has been updated', $permission->getName()));
        } else {
            $io->note('nothing was done');
        }

        return Command::SUCCESS;
    }
}
