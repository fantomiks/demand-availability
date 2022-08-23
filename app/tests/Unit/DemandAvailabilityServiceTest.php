<?php

namespace App\Tests\Unit;

use App\Dto\Response\EventResponseDto;
use App\Dto\Response\Mapper\CampervanDtoMapper;
use App\Dto\Response\Mapper\CustomerDtoMapper;
use App\Dto\Response\Mapper\DemandDtoMapper;
use App\Dto\Response\Mapper\DemandItemDtoMapper;
use App\Dto\Response\Mapper\EquipmentDtoMapper;
use App\Dto\Response\Mapper\StationDtoMapper;
use App\Entity\Campervan;
use App\Entity\Demand;
use App\Entity\DemandItem;
use App\Entity\Equipment;
use App\Entity\Station;
use App\Entity\User;
use App\Repository\DemandItemRepository;
use App\Repository\DemandRepository;
use App\Service\DemandAvailabilityService;
use PHPUnit\Framework\TestCase;

class SalaryCalculatorTest extends TestCase
{
    public function testCalculateAvailableItems()
    {
        $customer = $this->getCustomer(1);
        $campervan = $this->getCampervan(1);
        $station = $this->getStation(1);
        $equipment1 = $this->getEquipment(1, 'Equipment - 1');
        $equipment2 = $this->getEquipment(2, 'Equipment - 2');

        $demand1 = $this->getDemand(1, $customer, $station, $campervan, '2022-07-10');
        $demand2 = $this->getDemand(2, $customer, $station, $campervan, '2022-08-10');

        $demand1Item1 = $this->getDemandItem(1, $demand1, $equipment1, 1);
        $demand2Item1 = $this->getDemandItem(2, $demand2, $equipment1, 2);
        $demand2Item2 = $this->getDemandItem(3, $demand2, $equipment2, 2);

        $demand1->addDemandItem($demand1Item1);
        $demand2->addDemandItem($demand2Item1);
        $demand2->addDemandItem($demand2Item2);

        $demandRepository = $this->createMock(DemandRepository::class);
        $demandItemRepository = $this->createMock(DemandItemRepository::class);

        $demandRepository->expects($this->any())
            ->method('findByStationAndRange')
            ->willReturn([$demand2]);

        $demandRepository->expects($this->any())
            ->method('returnedCampervansByStationOnDate')
            ->willReturn([['id' => 1, 'qty' => 1]]);

        $demandItemRepository->expects($this->any())
            ->method('returnedEquipmentByStationOnDate')
            ->willReturn([['id' => 1, 'qty' => 1]]);

        $customerMapper = new CustomerDtoMapper();
        $stationMapper = new StationDtoMapper();
        $campervanMapper = new CampervanDtoMapper();
        $equipmentDtoMapper = new EquipmentDtoMapper();
        $demandItemDtoMapper = new DemandItemDtoMapper($equipmentDtoMapper);
        $mapper = new DemandDtoMapper($customerMapper, $stationMapper, $campervanMapper, $demandItemDtoMapper);

        $demandAvailabilityService = new DemandAvailabilityService($demandRepository, $demandItemRepository, $mapper);

        /** @var EventResponseDto[] $events */
        $events = $demandAvailabilityService->calculateAvailableItems(1, new \DateTime('2022-08-01'));
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(2, $event->id);
        $this->assertEquals('Demand #2', $event->title);
        $this->assertEquals('2022-08-10', $event->start);
        $this->assertEquals(1, $event->demand->available);
        $this->assertEquals(1, $event->demand->getQty());

        $demandItems = $event->demand->demandItems;
        $this->assertCount(2, $demandItems);
        $this->assertEquals('Equipment - 1', $demandItems[0]->equipment->name);
        $this->assertEquals(2, $demandItems[0]->getQty());
        $this->assertEquals(1, $demandItems[0]->available);
        $this->assertEquals('Equipment - 2', $demandItems[1]->equipment->name);
        $this->assertEquals(2, $demandItems[1]->getQty());
        $this->assertEquals(0, $demandItems[1]->available);



    }

    private function getCustomer(int $id): User
    {
        $customer = new User();
        $customer->setEmail('test@example.com');
        $this->set($customer, $id);
        return $customer;
    }

    private function getStation(int $id): Station
    {
        $station = new Station();
        $station->setName('Station');
        $this->set($station, $id);
        return $station;
    }

    private function getCampervan(int $id): Campervan
    {
        $campervan = new Campervan();
        $campervan->setName('Campervan');
        $this->set($campervan, $id);
        return $campervan;
    }

    private function getEquipment(int $id, string $name): Equipment
    {
        $equipment = new Equipment();
        $equipment->setName($name);
        $this->set($equipment, $id);
        return $equipment;
    }

    private function getDemand(int $id, User $customer, Station $station, Campervan $campervan, string $start): Demand
    {
        $start = new \DateTime($start);
        $end = (clone $start)->modify('-3 days');
        $demand = new Demand();
        $demand->setCustomer($customer);
        $demand->setRentalStart($start);
        $demand->setRentalEnd($end);
        $demand->setStartStation($station);
        $demand->setEndStation($station);
        $demand->setCampervan($campervan);
        $this->set($demand, $id);

        return $demand;
    }

    private function getDemandItem(int $id, Demand $demand, Equipment $equipment, int $qty): DemandItem
    {
        $demandItem = new DemandItem();
        $demandItem->setDemand($demand);
        $demandItem->setEquipment($equipment);
        $demandItem->setQty($qty);
        $this->set($demandItem, $id);

        return $demandItem;
    }

    public function set($entity, $value, $propertyName = 'id')
    {
        $class = new \ReflectionClass($entity);
        $property = $class->getProperty($propertyName);

        $property->setValue($entity, $value);
    }
}
