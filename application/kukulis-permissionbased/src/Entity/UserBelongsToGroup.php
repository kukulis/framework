<?php

namespace Kukulis\PermissionBased\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Kukulis\PermissionBased\Repository\UserBelongsToGroupRepository;

#[ORM\Entity(repositoryClass: UserBelongsToGroupRepository::class)]
class UserBelongsToGroup
{

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\Id]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Group::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\Id]
    private ?Group $group = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }
}
