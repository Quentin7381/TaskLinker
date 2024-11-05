<?php

namespace App\Factory;

use App\Entity\Task;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Project;
use App\Entity\Employee;
use App\Entity\Status;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function class(): string
    {
        return Task::class;
    }

    protected function defaults(): array|callable
    {
        $projectRepository = $this->entityManager->getRepository(Project::class);
        $employeeRepository = $this->entityManager->getRepository(Employee::class);
        
        $projects = $projectRepository->getRandom();
        $project = $projects[0];
        
        /**
         * @var Doctrine\ORM\PersistentCollection $availableTags
         */
        $availableTags = $project->getAllowedTags();

        /**
         * @var Doctrine\ORM\PersistentCollection $availableStatuses
         */
        $availableStatuses = $project->getAllowedStatuses();

        // to arrays
        $availableTags = $availableTags->toArray();
        $availableStatuses = $availableStatuses->toArray();

        $status = self::faker()->randomElement($availableStatuses);
        if(empty($status)) {
            $status = $this->entityManager->getRepository(Status::class)->getRandom()[0];
        }
        
        return [
            'name' => self::faker()->word(),
            'description' => self::faker()->sentence(),
            'endDate' => self::faker()->dateTimeBetween('now', '+1 year'),
            'status' => $status,
            'tags' => self::faker()->randomElements($availableTags, self::faker()->numberBetween(1, 4)),
            'project' => $project,
            'assignee' => @end($employeeRepository->getRandom()),
        ];
    }
}
