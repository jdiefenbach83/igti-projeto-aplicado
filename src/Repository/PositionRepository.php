<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class PositionRepository implements PositionRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Position::class);
    }

    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function add(Position $position): void
    {
        $this->entityManager->persist($position);
        $this->entityManager->flush();
    }

    public function update(Position $position): void
    {
        $this->entityManager->persist($position);
        $this->entityManager->flush();
    }

    public function remove(Position $position): void
    {
        $this->entityManager->remove($position);
        $this->entityManager->flush();
    }
}
