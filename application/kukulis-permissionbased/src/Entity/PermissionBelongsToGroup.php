<?php

namespace Kukulis\PermissionBased\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kukulis\PermissionBased\Repository\PermissionBelongsToGroupRepository;

#[ORM\Entity(repositoryClass: PermissionBelongsToGroupRepository::class)]
#[ORM\Table(name: 'permissions_belongs_to_group')]
//#[ORM\UniqueConstraint(columns: ['group_id', 'permission_id'])]

class PermissionBelongsToGroup
{
    #[ORM\ManyToOne(targetEntity: Group::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\Id]
    private ?Group $group = null;

    #[ORM\ManyToOne(targetEntity: Permission::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'permission_id', referencedColumnName: 'id', fieldName: 'fk_group_permission')]
    #[ORM\Id]
    private ?Permission $permission = null;


    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }

    public function getPermission(): ?Permission
    {
        return $this->permission;
    }

    public function setPermission(?Permission $permission): void
    {
        $this->permission = $permission;
    }
}
