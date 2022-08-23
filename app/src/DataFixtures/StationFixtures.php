<?php

namespace App\DataFixtures;

use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StationFixtures extends Fixture
{
    public const STATION_NAMES = ['Munich', 'Paris', 'Porto', 'Madrid'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STATION_NAMES as $name) {
            $entity = $this->createEntity($name);
            $manager->persist($entity);
            $manager->flush();
            $this->addReference($name, $entity);
        }
    }

    private function createEntity(string $name): Station
    {
        $model = new Station();
        $model->setName($name);
        return $model;
    }
}
