<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository$offset = null)
 */
abstract class BaseRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }
    
    public function getRandom(int $times = 1): array {
        $all = $this->findAll();
        $count = count($all);

        if ($count < $times) {
            user_error('Tried to get ' . $times . ' random entities, but only ' . $count . ' available. Returning all available entities.', E_USER_WARNING);
        }

        if($count <= $times) {
            return $all;
        }
        
        $return = [];
        for ($i = 0; $i < $times; $i++) {
            $max = count($all) - 1;
            $rand = rand(0, $max);
            $return[] = $all[$rand];

            unset($all[$rand]);
            $all = array_values($all);
        }

        $return = array_filter($return);

        return $return;
    }
}
