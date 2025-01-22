<?php

namespace Kukulis\PermissionBased\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kukulis\PermissionBased\Repository\PermissionBelongsToGroupRepository;

#[ORM\Entity(repositoryClass: PermissionBelongsToGroupRepository::class)]
#[ORM\Table(name: 'permissions_belongs_to_group')]
//#[ORM\UniqueConstraint(columns: ['group_id', 'permission_id'])]

class PermissionBelongsToGroup
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Group::class)]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\Id]
    private ?Group $group = null;

    #[ORM\ManyToOne(targetEntity: Permission::class)]
    #[ORM\JoinColumn(name: 'permission_id', referencedColumnName: 'id', fieldName: 'fk_group_permission')]
    #[ORM\Id]
    private ?Permission $permission = null;
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function setId(?int $id): void
//    {
//        $this->id = $id;
//    }

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
