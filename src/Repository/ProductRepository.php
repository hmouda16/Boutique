<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function myFind($id)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id);


        //on récupere la query a partir de queryBuider
        $query = $queryBuilder->getQuery();


        // on récupere les resultat a partir de la query
        $results = $query->getResult();


        //on retourne ce sesultat
        return $results;
    }



    public function myFindProduct($price1, $price2)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.price >= :price1')
            ->setParameter('price1', $price1 * 100)
            ->andWhere('p.price <= :price2')
            ->setParameter('price2', $price2 * 100)
            ->orderBy('p.price', 'DESC');
        $query = $queryBuilder->getQuery();


        // on récupere les resultat a partir de la query
        $results = $query->getResult();


        //on retourne ce sesultat
        return $results;
    }



    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
