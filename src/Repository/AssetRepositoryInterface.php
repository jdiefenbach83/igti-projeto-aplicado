<?php

namespace App\Repository;

use App\Entity\Asset;

interface AssetRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function add(Asset $asset): void;
    public function update(Asset $asset): void;
    public function remove(Asset $asset): void;
}