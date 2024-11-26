<?php

namespace App\Factory;

use App\Entity\Tag;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends PersistentProxyObjectFactory<Tag>
 */
final class TagFactory extends PersistentProxyObjectFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function class(): string
    {
        return Tag::class;
    }
    protected function defaults(): array|callable
    {
        $tagNames = [
            'Backend',
            'Frontend',
            'Bug',
            'Feature',
            'UI',
            'UX',
            'Database',
            'Security',
            'Accessibility',
            'Performance',
            'SEO',
            'Mobile',
            'Desktop',
        ];

        return [
            'name' => self::faker()->unique()->randomElement($tagNames),
        ];
    }
}
