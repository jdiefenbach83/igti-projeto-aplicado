<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
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

    private bool $isTransaction;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Position::class);
        $this->isTransaction = false;
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
        $this->processWorkUnit();
    }

    public function update(Position $position): void
    {
        $this->entityManager->persist($position);
        $this->processWorkUnit();
    }

    public function remove(Position $position): void
    {
        $this->entityManager->remove($position);
        $this->processWorkUnit();
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
            ->addOrderBy('lower(p.negotiationType)', 'ASC')
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
            ->innerJoin('p.asset', 'a')
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
            ->innerJoin('p.asset', 'a')
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
            ->innerJoin('p.asset', 'a')
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
