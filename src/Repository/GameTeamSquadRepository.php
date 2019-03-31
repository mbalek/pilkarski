<?php

namespace App\Repository;

use App\Entity\GameTeamSquad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameTeamSquad|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameTeamSquad|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameTeamSquad[]    findAll()
 * @method GameTeamSquad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameTeamSquadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameTeamSquad::class);
    }

    // /**
    //  * @return GameTeamSquad[] Returns an array of GameTeamSquad objects
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
    public function findOneBySomeField($value): ?GameTeamSquad
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
