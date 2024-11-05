<?php

namespace App\Factory;

use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Employee;
use App\Entity\Tag;

/**
 * @extends PersistentProxyObjectFactory<Project>
 */
final class ProjectFactory extends PersistentProxyObjectFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function class(): string
    {
        return Project::class;
    }

    protected function defaults(): array|callable
    {
        $startDate = self::faker()->dateTime();
        $duration = self::faker()->numberBetween(30, 365);
        $endDate = (clone $startDate)->modify("+$duration days");

        $statusRepository = $this->entityManager->getRepository(Status::class);
        $employeeRepository = $this->entityManager->getRepository(Employee::class);
        $tagRepository = $this->entityManager->getRepository(Tag::class);

        $statuses = $statusRepository->getRandom(self::faker()->numberBetween(2, 5));
        $statuses = array_filter($statuses);

        return [
            'name' => self::faker()->words(3, true),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'archived' => false,
            'allowedStatuses' => $statuses,
            'allowedTags' => $tagRepository->getRandom(self::faker()->numberBetween(4, 8)),
            'members' => $employeeRepository->getRandom(self::faker()->numberBetween(1, 10)),
        ];
    }
}
