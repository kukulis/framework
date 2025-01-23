<?php

namespace Kukulis\PermissionBased\Command;

use Kukulis\PermissionBased\Entity\PermissionBelongsToGroup;
use Kukulis\PermissionBased\Entity\UserBelongsToGroup;
use Kukulis\PermissionBased\Repository\GroupRepository;
use Kukulis\PermissionBased\Repository\PermissionBelongsToGroupRepository;
use Kukulis\PermissionBased\Repository\UserBelongsToGroupRepository;
use Kukulis\PermissionBased\Util\Grouper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:group:list',
    description: 'Lists groups',
)]
class GroupsListCommand extends Command
{
    public function __construct(
        private GroupRepository                    $groupRepository,
        private UserBelongsToGroupRepository       $userBelongsToGroupRepository,
        private PermissionBelongsToGroupRepository $permissionBelongsToGroupRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('show-permissions', 'p', InputOption::VALUE_NONE, 'Show assigned permissions to each group')
            ->addOption('show-users', 'u', InputOption::VALUE_NONE, 'Show assigned users to each group');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $groups = $this->groupRepository->findAll();
        $rows = array_map(fn($group) => [$group->getName()], $groups);
        $header = ['Name'];

        $showPermissions = $input->getOption('show-permissions');

        if ($showPermissions != null) {
            $header[] = 'Permissions';
            $permission2groups = $this->permissionBelongsToGroupRepository->findAll();
            $permission2groupsGrouped = Grouper::group($permission2groups,
                fn(PermissionBelongsToGroup $u2g) => $u2g->getGroup()->getName()
            );

            foreach ($rows as &$row) {
                $groupName = $row[0];
                if (array_key_exists($groupName, $permission2groupsGrouped)) {

                    /** @var PermissionBelongsToGroup[] $groupPermissions */
                    $groupPermissions = $permission2groupsGrouped[$groupName];

                    $permissionNames = array_map(fn(PermissionBelongsToGroup $p2g) => $p2g->getPermission()->getName(),
                        $groupPermissions
                    );

                    $row[] = implode(', ', $permissionNames);
                } else {
                    $row[] = '';
                }
            }
        }


        $showUsers = $input->getOption('show-users');

        if ($showUsers != null) {
            $header[] = 'Users';
            $users2groups = $this->userBelongsToGroupRepository->findAll();
            $users2groupsGrouped = Grouper::group($users2groups, fn(UserBelongsToGroup $u2g) => $u2g->getGroup()->getName());

            foreach ($rows as &$row) {
                $groupName = $row[0];
                if (array_key_exists($groupName, $users2groupsGrouped)) {

                    /** @var UserBelongsToGroup[] $groupUsers */
                    $groupUsers = $users2groupsGrouped[$groupName];

                    $userNames = array_map(fn(UserBelongsToGroup $u2g) => $u2g->getUser()->getUsername(), $groupUsers);

                    $row[] = implode(', ', $userNames);
                } else {
                    $row[] = '';
                }
            }
        }


        $io->table($header, $rows);

        return Command::SUCCESS;
    }
}
