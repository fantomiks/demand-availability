<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EquipmentFixtures extends Fixture
{
    public const EQUIPMENT_NAMES = ['Portable toilet', 'Bed sheet', 'Sleeping bag', 'Camping table', 'Chair'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EQUIPMENT_NAMES as $name) {
            $entity = $this->createEntity($name);
            $manager->persist($entity);
            $this->addReference($name, $entity);
        }

        $manager->flush();
    }

    private function createEntity(string $name): Equipment
    {
        $model = new Equipment();
        $model->setName($name);
        return $model;
    }
}
