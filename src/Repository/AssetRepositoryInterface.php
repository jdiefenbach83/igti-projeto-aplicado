<?php

namespace App\Repository;

use App\Entity\Asset;

interface AssetRepositoryInterface extends WorkUnitInterface
{
    public function findAll();
    public function findById(int $id): ?Asset;
    public function findByCode(string $code): ?Asset;
    public function save(Asset $asset): void;
    public function remove(Asset $asset): void;
}