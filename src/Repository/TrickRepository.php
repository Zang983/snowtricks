<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Trick>
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function findPaginate(Request $request): Array
    {
        $resultLimit = 10;
        $offset = $request->attributes->getInt('page') - 1 >= 0 ? $request->attributes->getInt('page') - 1 : 0;
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery();
        $paginator = new Paginator($query);
        $paginator->getQuery()->setFirstResult($offset * $resultLimit)
            ->setMaxResults($resultLimit);

        return [
            "datas" => $paginator,
            "count" => $paginator->count(),
            "nextPageExists" => ($paginator->count() > ($offset + 1) * $resultLimit)
        ];
    }

    public function findOneWithJoins(int $id): ?Trick
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.medias','m')
            ->leftJoin('t.user','u')
            ->leftJoin('t.category','c')
            ->leftJoin('t.comments','co')
            ->leftJoin('co.user','cu')
            ->addSelect('m')
            ->addSelect('u')
            ->addSelect('c')
            ->addSelect('co')
            ->addSelect('cu')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Trick[] Returns an array of Trick objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trick
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
