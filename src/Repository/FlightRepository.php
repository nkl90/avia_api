<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flight[]    findAll()
 * @method Flight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flight::class);
    }

    // /**
    //  * @return Flight[] Returns an array of Flight objects
    //  */
    
    public function getRandomActualFlightsIds(int $limit = 1, array $sortOrder = ['id', 'ASC']): ?array
    {
        //TODO: сделать эту выборку случайной
        $eArray = $this->createQueryBuilder('f')
            ->andWhere('f.status = :val')
            ->setParameter('val', Flight::STATUS_ACTUAL)
            ->orderBy('f.' . $sortOrder[0],$sortOrder[1])
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        
        $result = [];
        foreach($eArray as $entity){
            $result[] = $entity->getId();
        }
        
        return $result;
    }
   

    /*
    public function findOneBySomeField($value): ?Flight
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
