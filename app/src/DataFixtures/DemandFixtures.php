<?php

namespace App\DataFixtures;

use App\Entity\Campervan;
use App\Entity\Demand;
use App\Entity\DemandItem;
use App\Entity\Equipment;
use App\Entity\Station;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DemandFixtures extends Fixture implements DependentFixtureInterface
{
    private const EVENT_EVERY_N_DAY = 5;

    public function load(ObjectManager $manager): void
    {
        $startDate = new \DateTime('first day of previous month');

        for ($i = 0; $i <= 20; $i++) {
            $entity = $this->createEntity(clone $startDate, mt_rand(2, 10));
            $manager->persist($entity);

            $items = $this->createDemandItems($entity);
            foreach ($items as $item) {
                $manager->persist($item);
            }

            $startDate->modify('+5 days');
        }

        $manager->flush();
    }

    private function createEntity(\DateTime $rentalStart, int $duration): Demand
    {
        $model = new Demand();
        $model->setCampervan($this->randomCampervan());
        $model->setCustomer($this->randomCustomer());
        $model->setStartStation($this->randomStation());
        $model->setEndStation($this->randomStation());
        $model->setRentalStart($rentalStart);
        $model->setRentalEnd((clone $rentalStart)->modify("+$duration day"));

        return $model;
    }

    private function createDemandItems(Demand $demand): array
    {
        $demandItems = [];
        foreach (EquipmentFixtures::EQUIPMENT_NAMES as $equipmentName) {
            $qty = mt_rand(0, 5);
            if ($qty > 0) {
                $demandItem = new DemandItem();
                $demandItem->setDemand($demand);
                $demandItem->setEquipment($this->getEquipment($equipmentName));
                $demandItem->setQty($qty);

                $demandItems[] = $demandItem;
            }
        }

        return $demandItems;
    }

    /** @return Campervan */
    private function randomCampervan(): object
    {
        $randomKey = array_rand(CampervanFixtures::CAMPERVAN_NAMES);
        return $this->getReference(CampervanFixtures::CAMPERVAN_NAMES[$randomKey]);
    }

    /** @return Station */
    private function randomStation(): object
    {
        $randomKey = array_rand(StationFixtures::STATION_NAMES);
        return $this->getReference(StationFixtures::STATION_NAMES[$randomKey]);
    }

    /** @return User */
    private function randomCustomer(): object
    {
        $randomKey = array_rand(CustomerFixtures::CUSTOMERS_NAMES);
        return $this->getReference(CustomerFixtures::CUSTOMERS_NAMES[$randomKey]);
    }

    /** @return Equipment */
    private function getEquipment(string $name): object
    {
        return $this->getReference($name);
    }

    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class,
            CampervanFixtures::class,
            EquipmentFixtures::class,
            StationFixtures::class,
        ];
    }


}
