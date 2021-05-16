<?php

namespace App\Repository;

use App\Entity\Consolidation;
use App\Entity\PreConsolidation;
use Doctrine\ORM\EntityManagerInterface;

class ConsolidationRepository extends AbstratctRepository implements ConsolidationRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Consolidation::class);
    }

    public function findAll(): array
    {
        $order = [
            'negotiationType' => 'ASC',
            'marketType' => 'ASC',
            'year' => 'ASC',
            'month' => 'ASC',
        ];

        return $this->objectRepository->findBy([], $order);
    }

    public function findConsolidatePositions(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'pc.negotiationType',
                'a.type as assetType',
                'pc.year',
                'pc.month',
                'SUM(pc.result) as result',
            ])
            ->from(PreConsolidation::class, 'pc')
            ->innerJoin('pc.asset', 'a')
            ->addGroupBy('pc.negotiationType')
            ->addGroupBy('a.type')
            ->addGroupBy('pc.year')
            ->addGroupBy('pc.month')
            ->having('SUM(pc.result) <> 0')
            ->addOrderBy('pc.negotiationType', 'ASC')
            ->addOrderBy('a.type', 'ASC')
            ->addOrderBy('pc.year', 'ASC')
            ->addOrderBy('pc.month', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function add(Consolidation $consolidation): void
    {
        $this->entityManager->persist($consolidation);
        $this->processWorkUnit();
    }

    public function remove(Consolidation $consolidation): void
    {
        $this->entityManager->remove($consolidation);
        $this->processWorkUnit();
    }
}
