<?php

namespace App\Repository;

use App\Entity\Demand;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demand>
 *
 * @method Demand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demand[]    findAll()
 * @method Demand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demand::class);
    }

    public function save(Demand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Demand[] Returns an array of Demand objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demand
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//////////////////////////////////////////////////////////////////////////////////
    
    public function getAllDemands() {
        // automatically knows to select Demand
        // the "d" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('d')
               ->orderBy('d.date_created', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();

    }
    
    public function getMyDemands($user) {
        // automatically knows to select Demand
        // the "d" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('d')
            ->where('d.user_id = :id')
            ->setParameter('id', $user->getId())
            ->orderBy('d.date_created', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();

    }
    
    
}
