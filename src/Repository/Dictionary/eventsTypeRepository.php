<?php

namespace App\Repository\Dictionary;

use App\Entity\Dictionary\eventsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method eventsType|null find($id, $lockMode = null, $lockVersion = null)
 * @method eventsType|null findOneBy(array $criteria, array $orderBy = null)
 * @method eventsType[]    findAll()
 * @method eventsType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class eventsTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, eventsType::class);
    }

    // /**
    //  * @return eventsType[] Returns an array of eventsType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?eventsType
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
