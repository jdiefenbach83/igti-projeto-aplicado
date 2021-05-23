<?php

namespace App\Repository;

use App\Entity\Broker;
use Doctrine\ORM\EntityManagerInterface;

class BrokerRepository extends AbstratctRepository implements BrokerRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Broker::class);
    }

    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function save(Broker $broker): void
    {
        $this->entityManager->persist($broker);
        $this->processWorkUnit();
    }

    public function remove(Broker $broker): void
    {
        $this->entityManager->remove($broker);
        $this->processWorkUnit();
    }
}
