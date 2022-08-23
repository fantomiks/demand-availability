<?php

namespace App\DataFixtures;

use App\Entity\Campervan;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampervanFixtures extends Fixture
{
    public const CAMPERVAN_NAMES = ['Volkswagen', 'Mercedes-Benz', 'Ford'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CAMPERVAN_NAMES as $name) {
            $entity = $this->createEntity($name);
            $manager->persist($entity);
            $this->addReference($name, $entity);
        }

        $manager->flush();
    }

    private function createEntity(string $name): Campervan
    {
        $model = new Campervan();
        $model->setName($name);
        return $model;
    }
}
