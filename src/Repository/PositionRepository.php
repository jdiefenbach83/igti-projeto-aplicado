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


    public function findDayTradeNegotiations(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'p.date',
                'a.id as asset_id',
                'SUM(IFELSE(p.type=\'BUY\', p.quantity, 0)) as quantity_buy',
                'SUM(IFELSE(p.type=\'SELL\', p.quantity, 0)) as quantity_sell',
            ])
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a')
            ->groupBy('p.date, a.id')
            ->having('COUNT(DISTINCT p.type) = 2')
            ->orderBy('p.date', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findDayNormalNegotiations(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select([
                'a.id as asset_id',
                'SUM(IFELSE(p.type=\'BUY\', p.quantity, 0)) as quantity_buy',
                'SUM(IFELSE(p.type=\'SELL\', p.quantity, 0)) as quantity_sell',
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

    public function findByAssetAndTypeAndDate(int $assetId, string $type, \DateTimeImmutable $date = null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select(array('p'))
            ->from(Position::class, 'p')
            ->innerJoin('p.asset', 'a')
            ->where(
                $queryBuilder->expr()->eq('a.id', ':assetId'),
                $queryBuilder->expr()->eq('p.type', ':type'),
                $queryBuilder->expr()->gt('p.quantityBalance', '0'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('assetId', $assetId),
                new Parameter('type', $type),
            ]))
            ->orderBy('p.date', 'ASC');

        if ($date) {
            $query->andWhere(
                $queryBuilder->expr()->eq('p.date', ':date'),
            );

            $query->getParameters()->add(new Parameter('date', $date));
        }

        $query = $query->getQuery();

        return $query->getResult();
    }
}
