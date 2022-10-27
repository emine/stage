<?php

namespace App\Repository;

use App\Entity\Relation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relation>
 *
 * @method Relation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relation[]    findAll()
 * @method Relation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relation::class);
    }

    public function save(Relation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Relation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Relation[] Returns an array of Relation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Relation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    
////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////
    
    public function getAllRelations($demand) {
        // automatically knows to select Relation
        // the "r" is an alias you'll use in the rest of the query
        return $this->createQueryBuilder('r')
                    ->where('r.id_demand = :id')
                    ->setParameter('id', $demand->getId())
                    ->getQuery()
                    ->getResult();
    }    
    
    public function sentRelations($user) {
        // contacts made by user
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT u.email, d.id, d.title, d.photo, d.date_created, r.id as id_relation 
            FROM relation r, demand d, user u 
            WHERE d.id = r.id_demand 
            AND r.id_user = :id
            AND u.id = d.user_id 
            ORDER BY d.date_modified DESC;
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $user->getId()]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();    }
     
    public function receivedRelations($user) {
        // contacts made to user demands
       $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT u.email, d.id, d.title, d.photo, d.date_created , r.id as id_relation 
            FROM user u, relation r LEFT JOIN demand d ON d.id = r.id_demand
            WHERE d.user_id = :id
            AND u.id = r.id_user
            ORDER BY d.date_modified DESC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $user->getId()]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    
    
    
    
}
