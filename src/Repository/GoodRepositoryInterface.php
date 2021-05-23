<?php

namespace App\Repository;

use App\Entity\Good;

interface GoodRepositoryInterface extends WorkUnitInterface
{
    public function findAll(): array;
    public function findPositionsToExtractGoods(int $year): ?array;
    public function findGood(int $assetId, int $sequence);
    public function save(Good $good): void;
    public function remove(Good $good): void;
}