<?php

namespace App\Factory;

use App\Entity\Status;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends PersistentProxyObjectFactory<Status>
 */
final class StatusFactory extends PersistentProxyObjectFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function class(): string
    {
        return Status::class;
    }
    protected function defaults(): array|callable
    {
        $statusNames = [
            'New',
            'In Progress',
            'Review',
            'Done',
            'Blocked',
            'Cancelled',
            'Closed',
        ];

        return [
            'name' => self::faker()->unique()->randomElement($statusNames),
        ];
    }
}
