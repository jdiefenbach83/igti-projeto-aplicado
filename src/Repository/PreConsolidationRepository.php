<?php

namespace App\Repository;

use App\Entity\Position;
use App\Entity\PreConsolidation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;

class PreConsolidationRepository extends AbstratctRepository implements PreConsolidationRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, PreConsolidation::class);
    }

    public function findAll()
    {
        $order = [
            'asset' => 'ASC',
            'assetType' => 'ASC',
            'negotiationType' => 'ASC',
            'marketType' => 'ASC',
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
                "SUM(IFELSE(p.type='SELL', p.totalOperation, 0)) as sales_total",
            ])
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a', Join::WITH, 'p.asset = a.id')
            ->addGroupBy('assetId')
            ->addGroupBy('p.negotiationType')
            ->addGroupBy('year')
            ->addGroupBy('month')
            ->having('result <> 0')
            ->addOrderBy('a.id', 'ASC')
            ->addOrderBy('p.negotiationType', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function save(PreConsolidation $preConsolidation): void
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