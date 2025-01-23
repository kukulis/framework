<?php

namespace Kukulis\PermissionBased\Command;

use App\Repository\UserRepository;
use Kukulis\PermissionBased\Repository\GroupRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:group:list',
    description: 'Lists groups',
)]
class GroupsListCommand extends Command
{
    public function __construct(
        private GroupRepository $groupRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $groups = $this->groupRepository->findAll();
        $rows =  array_map  ( fn($group)=>[$group->getName()], $groups );
        $io->table(['Name'], $rows);

        return Command::SUCCESS;
    }
}
