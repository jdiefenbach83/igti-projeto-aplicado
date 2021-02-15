<?php

namespace App\Repository;

use App\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class AssetRepository implements AssetRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Asset::class);
    }

    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function add(Asset $asset): void
    {
        $this->entityManager->persist($asset);
        $this->entityManager->flush();
    }

    public function update(Asset $asset): void
    {
        $this->entityManager->persist($asset);
        $this->entityManager->flush();
    }

    public function remove(Asset $asset): void
    {
        $this->entityManager->remove($asset);
        $this->entityManager->flush();
    }
}
