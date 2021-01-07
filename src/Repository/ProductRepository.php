<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneBySomeField($value): ?Product
    {

        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.id = :id');

        if ($value == 1) {
            $qb = $qb->setParameter('id', $value)
                ->getQuery()
                ->getOneOrNullResult();
        } else {
            $qb = $qb->setParameter('id', $value + 1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        return $qb;
    }


    public function findSQLPure($value)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM product P INNER JOIN category C ON C.id = P.category_id WHERE C.id = $value";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}
