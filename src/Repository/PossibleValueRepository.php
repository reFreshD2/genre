<?php

namespace App\Repository;

use App\Entity\PossibleValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PossibleValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method PossibleValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method PossibleValue[]    findAll()
 * @method PossibleValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PossibleValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PossibleValue::class);
    }

    // /**
    //  * @return PossibleValue[] Returns an array of PossibleValue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PossibleValue
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
