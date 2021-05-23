<?php

namespace App\Repository;

use App\Entity\Asset;
use App\Entity\Broker;
use App\Entity\BrokerageNote;
use App\Entity\Company;
use App\Entity\Good;
use App\Entity\Operation;
use App\Entity\Position;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;

class GoodRepository extends AbstratctRepository implements GoodRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Good::class);
    }

    public function findAll(): array
    {
        $order = [
            'year' => 'ASC',
        ];

        return $this->objectRepository->findBy([], $order);
    }

    public function findPositionsToExtractGoods(int $year): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        $query = $queryBuilder
            ->select([
                'a.id as asset_id',
                'MAX(p.sequence) as sequence',
            ])
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a')
            ->where(
                $queryBuilder->expr()->between('p.date', ':startDate', ':endDate'),
                $queryBuilder->expr()->eq('a.type', ':assetType'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('startDate', $startDate),
                new Parameter('endDate', $endDate),
                new Parameter('assetType', Asset::TYPE_STOCK),
            ]))
            ->addGroupBy('a.id')
            ->getQuery();

        return $query->getResult();
    }

    public function findGood(int $assetId, int $sequence)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'a.id as assetId',
                'c.cnpj as companyCNPJ',
                'c.name as companyName',
                'p.accumulatedQuantity as quantity',
                'b.cnpj as brokerCNPJ',
                'b.name as brokerName',
                'p.averagePriceToIr as price',
                'p.sequence'
            ])
            ->from(Position::class, 'p')
            ->innerJoin(Asset::class, 'a', Join::WITH, 'p.asset = a.id')
            ->innerJoin(Company::class, 'c', Join::WITH, 'a.company = c.id')
            ->innerJoin(Operation::class, 'o', Join::WITH, 'p.operation = o.id')
            ->innerJoin(BrokerageNote::class, 'bn', Join::WITH, 'o.brokerageNote = bn.id')
            ->innerJoin(Broker::class, 'b', Join::WITH, 'bn.broker = b.id')
            ->where(
                $queryBuilder->expr()->eq('a.id',':assetId'),
                $queryBuilder->expr()->eq('p.sequence', ':sequence'),
                $queryBuilder->expr()->gt('p.accumulatedQuantity', 0),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('assetId', $assetId),
                new Parameter('sequence', $sequence),
            ]))
            ->getQuery();

        return $query->getResult();
    }

    public function save(Good $good): void
    {
        $this->entityManager->persist($good);
        $this->processWorkUnit();
    }

    public function remove(Good $good): void
    {
        $this->entityManager->remove($good);
        $this->processWorkUnit();
    }
}