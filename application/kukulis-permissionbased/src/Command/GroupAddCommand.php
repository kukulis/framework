<?php

namespace Kukulis\PermissionBased\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Kukulis\PermissionBased\Entity\Group;
use Kukulis\PermissionBased\Repository\GroupRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:group:add',
    description: 'Add group',
)]
class GroupAddCommand extends Command
{
    public function __construct(
        private GroupRepository        $groupRepository,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Group name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');

        $existingGroup = $this->groupRepository->findOneByName($name);
        if ($existingGroup != null) {
            $io->error(sprintf('Group [%s] already exists with id [%s]', $name, $existingGroup->getId()));

            return Command::FAILURE;
        }

        $group = new Group();
        $group->setName($name);

        $this->groupRepository->create($group);
        $this->entityManager->flush();

        $io->success(sprintf('Group "%s" successfully added with id [%s].', $name, $group->getId()));

        return Command::SUCCESS;
    }
}
