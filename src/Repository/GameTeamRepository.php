<?php

namespace App\Repository;

use App\Entity\GameTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameTeam[]    findAll()
 * @method GameTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameTeamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameTeam::class);
    }

    // /**
    //  * @return GameTeam[] Returns an array of GameTeam objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameTeam
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
