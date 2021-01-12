<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    // /**
    //  * @return Operation[] Returns an array of Operation objects
    //  */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user <= :val')
            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Operation
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
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
//            $qb->andWhere('u.dateCreation LIKE :filter OR u.user.id')
            $qb->leftJoin('u.station', 's')
                ->leftJoin('u.panel','p')
                ->andWhere('s.nom LIKE :filter OR p.description LIKE :filter OR u.dateCreation LIKE :filter')
                ->setParameter('filter', '%'.$filter.'%');
        }
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function CountOperations(){
        $query= $this->getEntityManager()->createQuery('SELECT COUNT (e) FROM App:Operation e ');
        $result=$query->getSingleResult();
        return $result;
    }

    public function findAllOperationsByEveryRegion(){
        $op =  $this->createQueryBuilder('o')
            ->getQuery()
            ->getResult()
            ;


    }
}


