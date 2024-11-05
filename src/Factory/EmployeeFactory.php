<?php

namespace App\Factory;

use App\Entity\Employee;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends PersistentProxyObjectFactory<Employee>
 */
final class EmployeeFactory extends PersistentProxyObjectFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function class(): string
    {
        return Employee::class;
    }
    protected function defaults(): array|callable
    {
        return [
            'first_name' => self::faker()->firstName(),
            'last_name' => self::faker()->lastName(),
            'email' => self::faker()->unique()->safeEmail(),
            'enabled' => true,
            'password' => self::faker()->password(),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
