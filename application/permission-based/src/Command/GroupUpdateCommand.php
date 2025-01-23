<?php

namespace Kukulis\PermissionBased\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Kukulis\PermissionBased\Entity\PermissionBelongsToGroup;
use Kukulis\PermissionBased\Entity\UserBelongsToGroup;
use Kukulis\PermissionBased\Repository\GroupRepository;
use Kukulis\PermissionBased\Repository\PermissionBelongsToGroupRepository;
use Kukulis\PermissionBased\Repository\PermissionRepository;
use Kukulis\PermissionBased\Repository\UserBelongsToGroupRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'permission:group:update',
    description: 'Add group',
)]
class GroupUpdateCommand extends Command
{
    public function __construct(
        private GroupRepository                    $groupRepository,
        private PermissionRepository               $permissionRepository,
        private UserRepository                     $userRepository,
        private PermissionBelongsToGroupRepository $permissionBelongsToGroupRepository,
        private UserBelongsToGroupRepository       $userBelongsToGroupRepository,
        private EntityManagerInterface             $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Group name')
            ->addOption('add-permission', 'a', InputOption::VALUE_REQUIRED, 'Permission name to add')
            ->addOption('remove-permission', 'r', InputOption::VALUE_REQUIRED, 'Permission name to remove')
            ->addOption('add-user', 'A', InputOption::VALUE_REQUIRED, 'User name to add')
            ->addOption('remove-user', 'R', InputOption::VALUE_REQUIRED, 'User name to remove');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');

        $group = $this->groupRepository->findOneByName($name);
        if ($group == null) {
            $io->error(sprintf('Group [%s] does not exist', $name));

            return Command::FAILURE;
        }

        try {
            $addPermission = $input->getOption('add-permission');
            if ($addPermission != null) {
                $permission = $this->permissionRepository->findOneByName($addPermission);
                if ($permission == null) {
                    $io->error(sprintf('Permission [%s] does not exist', $addPermission));
                    return Command::FAILURE;
                }

                $permission2group = $this->permissionBelongsToGroupRepository->findAssignment($group->getId(), $permission->getId());
                if ($permission2group != null) {
                    $io->error(sprintf('Permission [%s] already assigned to group [%s]', $addPermission, $group->getName()));
                    return Command::FAILURE;
                }

                $permission2group = new PermissionBelongsToGroup();
                $permission2group->setPermission($permission);
                $permission2group->setGroup($group);
                $this->permissionBelongsToGroupRepository->create($permission2group);
                $this->entityManager->flush();
                $io->success(sprintf('Permission [%s] was successfully added to group [%s]', $addPermission, $group->getName()));

                return Command::SUCCESS;
            }

            $removePermission = $input->getOption('remove-permission');
            if ($removePermission != null) {
                $permission = $this->permissionRepository->findOneByName($removePermission);
                if ($permission == null) {
                    $io->error(sprintf('Permission [%s] does not exist', $addPermission));
                    return Command::FAILURE;
                }

                $permission2group = $this->permissionBelongsToGroupRepository->findAssignment($group->getId(), $permission->getId());

                if ($permission2group == null) {
                    $io->error(sprintf('Permission [%s] does not belong to group [%s]', $removePermission, $group->getName()));
                    return Command::FAILURE;
                }
                $this->permissionBelongsToGroupRepository->delete($permission2group);
                $this->entityManager->flush();
                $io->success(sprintf('Permission [%s] was successfully removed from group [%s]', $removePermission, $group->getName()));

                return Command::SUCCESS;
            }

            $addUser = $input->getOption('add-user');

            if ($addUser != null) {
                $user = $this->userRepository->findOneByUsername($addUser);
                if ($user == null) {
                    $io->error(sprintf('User [%s] does not exist', $addUser));

                    return Command::FAILURE;
                }

                $user2group = $this->userBelongsToGroupRepository->findAssignment(groupId: $group->getId(), userId: $user->getId());

                if ($user2group != null) {
                    $io->error(sprintf('User [%s] already belong to group [%s]', $addUser, $group->getName()));

                    return Command::FAILURE;
                }

                $user2group = new UserBelongsToGroup();
                $user2group->setGroup($group);
                $user2group->setUser($user);
                $this->userBelongsToGroupRepository->create($user2group);
                $this->entityManager->flush();
                $io->success(sprintf('User [%s] was successfully added to group [%s]', $addUser, $group->getName()));

                return Command::SUCCESS;
            }
            $removeUser = $input->getOption('remove-user');
            if ($removeUser != null) {
                $user = $this->userRepository->findOneByUsername($removeUser);
                if ($user == null) {
                    $io->error(sprintf('User [%s] does not exist', $addUser));

                    return Command::FAILURE;
                }

                $user2group = $this->userBelongsToGroupRepository->findAssignment(groupId: $group->getId(), userId: $user->getId());

                if ($user2group == null) {
                    $io->error(sprintf('User [%s] does not belong to group [%s]', $removeUser, $group->getName()));

                    return Command::FAILURE;
                }

                $this->userBelongsToGroupRepository->delete($user2group);
                $io->success(sprintf('User [%s] was successfully removed from group [%s]', $removeUser, $group->getName()));

                return Command::SUCCESS;
            }
        } finally {
            $this->entityManager->flush();
        }

        $io->note('Nothing to do');
        return Command::SUCCESS;
    }
}
