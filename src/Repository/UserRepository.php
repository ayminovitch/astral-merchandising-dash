<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
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
            $qb->andWhere('u.roles LIKE :filter OR u.username LIKE :filter OR u.email LIKE :filter OR u.nom LIKE :filter OR u.prenom LIKE :filter  ')
                ->setParameter('filter', '%'.$filter.'%');
        }
        return $qb
            ->getQuery()
            ->getResult();



    }

    public function CountUsers(){
        $query= $this->getEntityManager()->createQuery('SELECT COUNT (e) FROM App:User e ');
        $result=$query->getSingleResult();
        return $result;
    }
}
