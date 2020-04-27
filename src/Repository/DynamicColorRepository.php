<?php

namespace App\Repository;

use App\Entity\DynamicColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DynamicColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicColor[]    findAll()
 * @method DynamicColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicColor::class);
    }

    // /**
    //  * @return DynamicColor[] Returns an array of DynamicColor objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('d')
    ->andWhere('d.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('d.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
public function findOneBySomeField($value): ?DynamicColor
{
return $this->createQueryBuilder('d')
->andWhere('d.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}
