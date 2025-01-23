<?php

namespace Kukulis\PermissionBased\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kukulis\PermissionBased\Entity\Group;
use Kukulis\PermissionBased\Entity\Permission;
use Kukulis\PermissionBased\Entity\PermissionBelongsToGroup;

/**
 * @extends ServiceEntityRepository<PermissionBelongsToGroup>
 */
class PermissionBelongsToGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PermissionBelongsToGroup::class);
    }

    //    /**
    //     * @return PermissionBelongsToGroup[] Returns an array of PermissionBelongsToGroup objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PermissionBelongsToGroup
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAssignment(int $groupId, int $permissionId): ?PermissionBelongsToGroup
    {
        $groupReference = $this->getEntityManager()->getReference(Group::class, $groupId);
        $permissionReference = $this->getEntityManager()->getReference(Permission::class, $permissionId);

        return $this->findOneBy(['group' => $groupReference, 'permission' => $permissionReference]);
    }

    public function create(PermissionBelongsToGroup $permission2group): void
    {
        $this->getEntityManager()->persist($permission2group);
    }

    public function delete(PermissionBelongsToGroup $permission2group): void
    {
        $this->getEntityManager()->remove($permission2group);
    }
}
