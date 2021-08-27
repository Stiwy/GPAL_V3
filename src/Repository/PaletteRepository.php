<?php

namespace App\Repository;

use App\Entity\Palette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Palette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Palette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Palette[]    findAll()
 * @method Palette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaletteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Palette::class);
    }

    /**
    * @return Palette[] Returns an array of Palette objects
    */
    public function findByRef($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.reference LIKE :reference')
            ->setParameter('reference', "%" . $value . "%")
            ->orderBy('p.weg', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Palette
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
