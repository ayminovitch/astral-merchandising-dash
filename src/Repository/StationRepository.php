<?php

namespace App\Repository;

use App\Entity\Station;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Station|null find($id, $lockMode = null, $lockVersion = null)
 * @method Station|null findOneBy(array $criteria, array $orderBy = null)
 * @method Station[]    findAll()
 * @method Station[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }

    // /**
    //  * @return Station[] Returns an array of Station objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Station
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllQueryBuilder(string $filter )
    {
        $qb = $this->createQueryBuilder('u');
        if ($filter) {
            $qb->andWhere('u.nom LIKE :filter OR u.gouvernorat LIKE :filter OR u.adresse LIKE :filter    ')
                ->setParameter('filter', '%'.$filter.'%');
        }
        return $qb
            ->getQuery()
            ->getResult();

    }

    public function CountStations(){
        $query= $this->getEntityManager()->createQuery('SELECT COUNT (e) FROM App:Station e ');
        $result=$query->getSingleResult();
        return $result;
    }

    public function CountPanelsByStation(){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select($qb->expr()->count('p.panels'))
            ->from('App:Station', 'p');
        $query = $qb->getQuery();
        $panelsCount= $query->getSingleScalarResult();

        return $panelsCount;


    }
}
