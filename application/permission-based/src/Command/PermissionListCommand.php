<?php

namespace Kukulis\PermissionBased\Command;

use Kukulis\PermissionBased\Repository\PermissionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:list',
    description: 'Lists available permissions',
)]
class PermissionListCommand extends Command
{
    public function __construct(
        private PermissionRepository $permissionRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $permissions = $this->permissionRepository->findAll();
        $rows =  array_map  ( fn($permission)=>[$permission->getName(), $permission->getAction()], $permissions );
        $io->table(['Name', 'Action'], $rows);

        return Command::SUCCESS;
    }
}
