<?php

namespace App\Repository;

use App\Entity\ReferencesRegister;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReferencesRegister|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferencesRegister|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferencesRegister[]    findAll()
 * @method ReferencesRegister[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferencesRegisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReferencesRegister::class);
    }

    // /**
    //  * @return ReferencesRegister[] Returns an array of ReferencesRegister objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReferencesRegister
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
