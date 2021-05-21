<?php

namespace App\Repository;

use App\Entity\Broker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class BrokerRepository implements BrokerRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository
     */
    private ObjectRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Broker::class);
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
        $this->entityManager->flush();
    }

    public function remove(Broker $broker): void
    {
        $this->entityManager->remove($broker);
        $this->entityManager->flush();
    }
}
