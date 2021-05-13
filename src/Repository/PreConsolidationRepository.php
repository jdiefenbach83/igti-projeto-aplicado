<?php

namespace App\Repository;

use App\Entity\Position;
use App\Entity\PreConsolidation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class PreConsolidationRepository implements PreConsolidationRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository
     */
    private ObjectRepository $objectRepository;

    private bool $isTransaction;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(PreConsolidation::class);
        $this->isTransaction = false;
    }

    public function startWorkUnit(): void
    {
        $this->isTransaction = true;
    }

    public function endWorkUnit(): void
    {
        $this->isTransaction = false;
        $this->processWorkUnit();
    }

    private function processWorkUnit() : void
    {
        if ($this->isTransaction) {
            return;
        }

        $this->entityManager->flush();
    }

    public function findAll()
    {
        $order = [
            'asset' => 'ASC',
            'negotiationType' => 'DESC',
            'year' => 'ASC',
            'month' => 'ASC',
        ];

        return $this->objectRepository->findBy([], $order);
    }

    public function findPreConsolidatePositions(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'a.id as assetId',
                'p.negotiationType',
                'EXTRACT(YEAR FROM p.date) as year',
                'EXTRACT(MONTH FROM p.date) as month',
                'SUM(p.result) as result',
            ])
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a')
            ->addGroupBy('assetId')
            ->addGroupBy('p.negotiationType')
            ->addGroupBy('year')
            ->addGroupBy('month')
            ->having('SUM(p.result) <> 0')
            ->addOrderBy('a.id', 'ASC')
            ->addOrderBy('p.negotiationType', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function add(PreConsolidation $preConsolidation): void
    {
        $this->entityManager->persist($preConsolidation);
        $this->processWorkUnit();
    }

    public function remove(PreConsolidation $preConsolidation): void
    {
        $this->entityManager->remove($preConsolidation);
        $this->processWorkUnit();
    }
}