<?php

namespace App\Repository;

use App\Entity\FruitsLegumes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FruitsLegumes|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitsLegumes|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitsLegumes[]    findAll()
 * @method FruitsLegumes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitsLegumesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitsLegumes::class);
    }

    // /**
    //  * @return FruitsLegumes[] Returns an array of FruitsLegumes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FruitsLegumes
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
}
