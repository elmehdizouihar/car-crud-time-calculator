<?php
// src/Repository/CarRepository.php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function findByModelAndNotId(string $model, ?int $id): ?Car
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.model = :model')
            ->setParameter('model', $model);

        if ($id !== null) {
            $qb->andWhere('c.id != :id')
               ->setParameter('id', $id);
        }

        return $qb->getQuery()
            ->getOneOrNullResult();
    }
}