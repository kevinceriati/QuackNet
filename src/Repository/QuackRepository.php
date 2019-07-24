<?php

namespace App\Repository;

use App\Entity\Quack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quack[]    findAll()
 * @method Quack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuackRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quack::class);
    }

    public function searchByKeyword($val)
    {

        return $this->createQueryBuilder('p')// l'alia reprsente la table dans laquelle on est, ici 'p' => quack
        ->join('p.author', 'c')// Ici p.author vient faire la liaison avec la table Comment (p=>Quack mais "author" n'est pas dans Quanck, donc "c" devient l'alias de notre table de liaison avec l'entité Comment)
        ->addSelect('c')
            ->orWhere('c.duckname LIKE :val')
            ->orWhere('p.content LIKE :val')
            ->setParameter('val', '%' . $val . '%')
            ->getQuery()
            ->getResult();
    }

    public function searchById($val)
    {

        return $this->createQueryBuilder('p')// l'alia reprsente la table dans laquelle on est, ici 'p' => quack
            ->andWhere('p.id = :val')
            ->setParameter('val',  $val)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Quack[] Returns an array of Quack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quack
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
