<?php

namespace App\Service;

use App\Dto\Response\DemandResponseDto;
use App\Dto\Response\EventResponseDto;
use App\Dto\Response\Mapper\DemandDtoMapper;
use App\Dto\Stock\Stockable;
use App\Dto\Stock\StockItem;
use App\Repository\DemandItemRepository;
use App\Repository\DemandRepository;

class DemandAvailabilityService
{
    private DemandRepository $demandRepository;
    private DemandItemRepository $demandItemRepository;
    private DemandDtoMapper $mapper;

    public function __construct(
        DemandRepository $demandRepository,
        DemandItemRepository $demandItemRepository,
        DemandDtoMapper $mapper
    ) {
        $this->demandRepository = $demandRepository;
        $this->demandItemRepository = $demandItemRepository;
        $this->mapper = $mapper;
    }

    public function calculateAvailableItems(int $stationId, \DateTimeInterface $startDate): array
    {
        $endDate = (clone $startDate)->modify('last day of this month');

        $demands = $this->mapper->transformFromObjects(
            $this->demandRepository->findByStationAndRange($stationId, $startDate, $endDate)
        );

        $campervanStock = $this->getCampervanStock($stationId, $startDate);
        $equipmentStock = $this->getEquipmentStock($stationId, $startDate);

        $events = [];

        foreach ($demands as $demand) {
            $demand->available = $this->howMuchAvailable($campervanStock, $demand);
            foreach ($demand->demandItems as $demandItem) {
                $demandItem->available = $this->howMuchAvailable($equipmentStock, $demandItem);
            }
            $events[] = $this->createDemandEvent($demand);;
        }

        return $events;
    }

    private function getCampervanStock(int $stationId, \DateTimeInterface $startDate): ItemStock
    {
        $campervanStock = new ItemStock();
        foreach ($this->demandRepository->returnedCampervansByStationOnDate($stationId, $startDate) as $item) {
            $campervanStock->put(new StockItem($item['id'], $item['qty']));
        }
        return $campervanStock;
    }

    private function getEquipmentStock(int $stationId, \DateTimeInterface $startDate): ItemStock
    {
        $equipmentStock = new ItemStock();
        foreach ($this->demandItemRepository->returnedEquipmentByStationOnDate($stationId, $startDate) as $item) {
            $equipmentStock->put(new StockItem($item['id'], $item['qty']));
        }
        return $equipmentStock;
    }

    private function createDemandEvent(DemandResponseDto $demand): EventResponseDto
    {
        $event = new EventResponseDto();
        $event->demand = $demand;
        $event->id = $demand->id;
        $event->title = 'Demand #' . $demand->id;
        $event->start = $demand->rentalStartDate->format('Y-m-d');
//        $event->end = $demand->rentalEndDate->format('Y-m-d');

        return $event;
    }

    private function howMuchAvailable(ItemStock $stock, Stockable $item): int
    {
        $available = $stock->available($item);
        $stock->take($item);

        return $available;
    }
}
