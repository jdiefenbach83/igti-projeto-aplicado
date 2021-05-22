<?php

namespace App\Repository;

use App\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;

class AssetRepository extends AbstratctRepository implements AssetRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Asset::class);
    }

    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

    public function findById(int $id): ?Asset
    {
        return $this->objectRepository->find($id);
    }

    public function findByCode(string $code): ?Asset
    {
        return $this->objectRepository->findOneBy(['code' => $code]);
    }

    public function save(Asset $asset): void
    {
        $this->entityManager->persist($asset);
        $this->processWorkUnit();
    }

    public function remove(Asset $asset): void
    {
        $this->entityManager->remove($asset);
        $this->processWorkUnit();
    }
}
