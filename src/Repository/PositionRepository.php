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

    public function findAll(): array
    {
        $order = [
            'asset' => 'ASC',
            'sequence' => 'ASC'
        ];

        return $this->objectRepository->findBy([], $order);
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

    public function findAllAssets(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select('a.id')
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a')
            ->distinct()
            ->getQuery();

        return $query->getResult();
    }

    public function findByAsset(int $assetId)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select(array('p'))
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a')
            ->where(
                $queryBuilder->expr()->eq('a.id', $assetId),
            )
            ->addOrderBy('p.date', 'ASC')
            ->addOrderBy('p.type', 'ASC')
            ->getQuery();

        return $query->getResult();
    }
}
