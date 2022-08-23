<?php

namespace App\Repository;

use App\Entity\Demand;
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

    public function add(Demand $entity, bool $flush = false): void
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

    /**
     * @return Demand[] Returns an array of Demand objects
     */
    public function findByStation(int $stationID): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.startStation = :station')
            ->setParameter('station', $stationID)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Demand[] Returns an array of Demand objects
     */
    public function findByStationAndRange(int $stationID, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.startStation = :station')
            ->setParameter('station', $stationID)
            ->andWhere("d.rentalStart BETWEEN :from AND :to")
            ->setParameter(':from', $from)
            ->setParameter(':to', $to)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function returnedCampervansByStationOnDate(int $stationID, \DateTimeInterface $dateTime) {
        return $this->createQueryBuilder('d')
            ->select('IDENTITY(d.campervan) as id', 'COUNT(d) as qty')
            ->andWhere('d.endStation = :station')
            ->setParameter('station', $stationID)
            ->andWhere("d.rentalEnd < :date")
            ->setParameter(":date", $dateTime->format('Y-m-d'))
            ->groupBy('d.campervan')
            ->getQuery()
            ->getResult();
    }
}
