<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;

class PositionRepository extends AbstratctRepository implements PositionRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Position::class);
    }

    public function findAll(): array
    {
        $order = [
            'asset' => 'ASC',
            'negotiationType' => 'DESC',
            'sequence' => 'ASC'
        ];

        return $this->objectRepository->findBy([], $order);
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function save(Position $position): void
    {
        $this->entityManager->persist($position);
        $this->processWorkUnit();
    }

    public function remove(Position $position): void
    {
        $this->entityManager->remove($position);
        $this->processWorkUnit();
    }

    public function findAllAssets(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select('a.id')
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a', Join::WITH, 'p.asset = a.id')
            ->distinct()
            ->getQuery();

        return $query->getResult();
    }

    public function findByAssetAndNegotiationType(int $assetId, string $negotiationType)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select(array('p'))
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a', Join::WITH, 'p.asset = a.id')
            ->where(
                $queryBuilder->expr()->eq('a.id',':assetId'),
                $queryBuilder->expr()->eq('p.negotiationType', ':negotiationType'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('assetId', $assetId),
                new Parameter('negotiationType', $negotiationType),
            ]))
            ->addOrderBy('p.date', 'ASC')
            ->addOrderBy('p.type', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findDayTradeNegotiations(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'p.date',
                'a.id as asset_id'
            ])
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a', Join::WITH, 'p.asset = a.id')
            ->groupBy('p.date, a.id')
            ->having('COUNT(DISTINCT p.type) = 2')
            ->orderBy('p.date', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findNormalNegotiations(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'a.id as asset_id'
            ])
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a', Join::WITH, 'p.asset = a.id')
            ->where(
                $queryBuilder->expr()->eq('p.negotiationType', ':negotiationType'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('negotiationType', Position::NEGOTIATION_TYPE_NORMAL),
            ]))
            ->groupBy('a.id')
            ->having('COUNT(DISTINCT p.type) = 2')
            ->getQuery();

        return $query->getResult();
    }

    public function findByAssetAndTypeAndDateAndNegotiationType(int $assetId, string $type, string $negotiationType, \DateTimeImmutable $date = null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select(array('p'))
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a', Join::WITH, 'p.asset = a.id')
            ->where(
                $queryBuilder->expr()->eq('a.id', ':assetId'),
                $queryBuilder->expr()->eq('p.type', ':type'),
                $queryBuilder->expr()->eq('p.negotiationType', ':negotiationType'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('assetId', $assetId),
                new Parameter('type', $type),
                new Parameter('negotiationType', Position::NEGOTIATION_TYPE_NORMAL),
            ]))
            ->addOrderBy('p.date', 'ASC')
            ->addOrderBy('p.quantity', 'DESC');

        if ($date) {
            $query->andWhere(
                $queryBuilder->expr()->eq('p.date', ':date'),
            );

            $query->getParameters()->add(new Parameter('date', $date));
        }

        if ($negotiationType === Position::NEGOTIATION_TYPE_DAYTRADE) {
            $query->andWhere(
                $queryBuilder->expr()->gt('p.quantityBalance', '0'),
            );
        }

        $query = $query->getQuery();

        return $query->getResult();
    }

    public function findDayTradeNegotiationsWithBalance(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select(array('p'))
            ->from(Position::class, 'p')
            ->where(
                $queryBuilder->expr()->eq('p.negotiationType', ':negotiationType'),
                $queryBuilder->expr()->gt('p.quantityBalance', '0'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('negotiationType', Position::NEGOTIATION_TYPE_DAYTRADE),
            ]))
            ->orderBy('p.date', 'ASC');

        $query = $query->getQuery();

        return $query->getResult();
    }
}
