<?php

namespace App\Repository;

use App\Entity\ValueOfFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ValueOfFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValueOfFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValueOfFeature[]    findAll()
 * @method ValueOfFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValueOfFeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValueOfFeature::class);
    }

    // /**
    //  * @return ValueOfFeature[] Returns an array of ValueOfFeature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ValueOfFeature
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
