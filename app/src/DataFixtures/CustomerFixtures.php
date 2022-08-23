<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public const CUSTOMERS_NAMES = ['consumer1@example.com', 'consumer2@example.com'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CUSTOMERS_NAMES as $name) {
            $entity = $this->createEntity($name);
            $manager->persist($entity);
            $this->addReference($name, $entity);
        }

        $manager->flush();
    }

    private function createEntity(string $email): User
    {
        $model = new User();
        $model->setEmail($email);

        return $model;
    }
}
