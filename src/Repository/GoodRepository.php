<?php

namespace App\Repository;

use App\Entity\Good;
use Doctrine\ORM\EntityManagerInterface;

class GoodRepository extends AbstratctRepository implements GoodRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Good::class);
    }

    public function findAll(): array
    {
        $order = [
            'year' => 'ASC',
        ];

        return $this->objectRepository->findBy([], $order);
    }

    public function save(Good $good): void
    {
        $this->entityManager->persist($good);
        $this->processWorkUnit();
    }

    public function remove(Good $good): void
    {
        $this->entityManager->remove($good);
        $this->processWorkUnit();
    }
}