<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    public function add(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginationQuery()
    {
        return $this->createQueryBuilder('r')
            ->leftjoin('r.client', 'c')
            ->leftjoin('r.adresse', 'a')
            ->orderBy('r.date_controle', 'DESC')
            ->getQuery()
        ;
    }

    public function paginationQueryComplex(array $params, array $dateCon, array $eFiltres) 
    {
        $sql = "SELECT r.*, a.*, c.* from rendez_vous r join adresse a On r.adresse_id = a.id  join client c On r.client_id = c.id where";
        foreach ($params as $key => $value){
            $sql .= " $key LIKE  '%$value%' and";
        }
        foreach ($dateCon as $key => $value){
            $sql .= " r.date_controle $value and";
        }
        foreach ($eFiltres as $key => $value){
            $sql .= " $key is $value and";
        }
        $sql = substr($sql, 0, strlen($sql)-3);
        $sql .=" order by r.date_controle DESC";
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\RendezVous', 'r');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query;
    }

//    /**
//     * @return RendezVous[] Returns an array of RendezVous objects
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

//    public function findOneBySomeField($value): ?RendezVous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
