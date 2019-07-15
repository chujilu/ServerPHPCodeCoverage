<?php

namespace App\Repository;

use App\Entity\TaskHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TaskHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskHistory[]    findAll()
 * @method TaskHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskHistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TaskHistory::class);
    }

    // /**
    //  * @return TaskHistory[] Returns an array of TaskHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TaskHistory
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
