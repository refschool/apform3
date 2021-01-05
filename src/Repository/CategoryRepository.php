<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Category objects
     */

    // public function findProductsByCategory($value)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.id = :val')
    //         ->setParameter('val', $value)
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }



    public function findInnerJoin($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.products', 'p')
            ->andWhere('c.id = :id')
            ->setParameter('id', $value)
            ->getQuery()->getArrayResult();
        // ->getOneOrNullResult();
    }
}
