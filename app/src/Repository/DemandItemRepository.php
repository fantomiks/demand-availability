<?php

namespace App\Repository;

use App\Entity\Demand;
use App\Entity\DemandItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandItem>
 *
 * @method DemandItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandItem[]    findAll()
 * @method DemandItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandItem::class);
    }

    public function add(DemandItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function returnedEquipmentByStationOnDate(int $stationID, \DateTimeInterface $dateTime) {
        return $this->createQueryBuilder('di')
            ->select('IDENTITY(di.equipment) as id', 'SUM(di.qty) as qty')
            ->leftJoin('di.demand', 'd')
            ->andWhere('d.endStation = :station')
            ->setParameter('station', $stationID)
            ->andWhere("d.rentalEnd < :date")
            ->setParameter(":date", $dateTime->format('Y-m-d'))
            ->groupBy('di.equipment')
            ->getQuery()
            ->getResult();
    }
}
