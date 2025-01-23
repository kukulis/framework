<?php

namespace Kukulis\PermissionBased\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kukulis\PermissionBased\Entity\Group;
use Kukulis\PermissionBased\Entity\UserBelongsToGroup;

/**
 * @extends ServiceEntityRepository<UserBelongsToGroup>
 */
class UserBelongsToGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBelongsToGroup::class);
    }

//    /**
//     * @return UserBelongsToGroup[] Returns an array of UserBelongsToGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserBelongsToGroup
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//    public function findAssignment(int $groupId, int $userId) : ? UserBelongsToGroup {
//        return $this->findOneBy(['group_id' => $groupId, 'user_id' => $userId]);
//    }
    public function findAssignment(int $groupId, int $userId) : ? UserBelongsToGroup {

        $groupReference = $this->getEntityManager()->getReference(Group::class, $groupId);
        $userReference = $this->getEntityManager()->getReference(User::class, $userId);
        return $this->findOneBy(['group' => $groupReference, 'user' => $userReference]);
    }

    public function create(UserBelongsToGroup $user2group): void
    {
        $this->getEntityManager()->persist($user2group);
    }

    public function delete(UserBelongsToGroup $user2group): void
    {
        $this->getEntityManager()->remove($user2group);
    }

}
