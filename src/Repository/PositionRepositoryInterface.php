<?php

namespace App\Repository;

use App\Entity\Position;

interface PositionRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function add(Position $position): void;
    public function update(Position $position): void;
    public function remove(Position $position): void;
    public function findAllAssets(): array;
    public function findByAssetAndNegotiationType(int $assetId, string $negotiationType);
    public function findDayTradeNegotiations(): array;
    public function findNormalNegotiations(): array;
    public function findByAssetAndTypeAndDateAndNegotiationType(int $assetId, string $type, string $negotiationType, \DateTimeImmutable $date = null): array;
    public function findDayTradeNegotiationsWithBalance(): array;
}