<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Factory\EmployeeFactory;
use App\Factory\StatusFactory;
use App\Factory\TagFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        EmployeeFactory::createMany(10);
        StatusFactory::createMany(5);
        TagFactory::createMany(10);
        ProjectFactory::createMany(10);
        TaskFactory::createMany(50);
    }
}
