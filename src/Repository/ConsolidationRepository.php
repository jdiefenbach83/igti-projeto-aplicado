<?php

namespace App\Repository;

use App\Entity\Consolidation;
use App\Entity\PreConsolidation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;

class ConsolidationRepository extends AbstratctRepository implements ConsolidationRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Consolidation::class);
    }

    public function findAll(): array
    {
        $order = [
            'assetType' => 'ASC',
            'negotiationType' => 'ASC',
            'marketType' => 'ASC',
            'year' => 'ASC',
            'month' => 'ASC',
        ];

        return $this->objectRepository->findBy([], $order);
    }

    public function findYearsToConsolidate(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'pc.year'
            ])
            ->from(PreConsolidation::class, 'pc')
            ->distinct()
            ->addOrderBy('pc.year', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findConsolidatePositions(int $year, int $month, string $market, string $negotiation, string $asset): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'SUM(pc.result) as result',
            ])
            ->from(PreConsolidation::class, 'pc')
            ->innerJoin('pc.asset', 'a', Join::WITH, 'pc.asset = a.id')
            ->where(
                $queryBuilder->expr()->eq('pc.year',':year'),
                $queryBuilder->expr()->eq('pc.month',':month'),
                $queryBuilder->expr()->eq('pc.marketType',':market'),
                $queryBuilder->expr()->eq('pc.negotiationType',':negotiation'),
                $queryBuilder->expr()->eq('pc.assetType',':asset'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('year', $year),
                new Parameter('month', $month),
                new Parameter('market', $market),
                new Parameter('negotiation', $negotiation),
                new Parameter('asset', $asset),
            ]))
            ->having('SUM(pc.result) <> 0')
            ->getQuery();

        return $query->getResult();
    }

    public function save(Consolidation $consolidation): void
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
