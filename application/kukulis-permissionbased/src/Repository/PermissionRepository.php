<?php

namespace Kukulis\PermissionBased\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kukulis\PermissionBased\Entity\Permission;

/**
 * @extends ServiceEntityRepository<Permission>
 */
class PermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }

    //    /**
    //     * @return Permission[] Returns an array of Permission objects
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

    public function findOneByName(string $name): ?Permission
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByAction(string $action): ?Permission
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.action = :action')
            ->setParameter('action', $action)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function delete(Permission $permission): void
    {
        $this->getEntityManager()->remove($permission);
    }

    public function create(Permission $permission): void
    {
        $this->getEntityManager()->persist($permission);
    }

    public function update(Permission $permission): void
    {
        $this->getEntityManager()->persist($permission);
    }

    public function checkUserHasPermission(User $user, Permission $permission): bool
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = /** @lang MySQL */
            "select pg.permission_id from permissions_belongs_to_group pg
            join user_belongs_to_group ug
            on pg.group_id = ug.group_id
            where ug.user_id=:user_id and pg.permission_id=:permission_id";

        $exists = $connection
            ->executeQuery($sql, ['user_id' => $user->getId(), 'permission_id' => $permission->getId()])
            ->fetchOne();

        return $exists != null;
    }
}
