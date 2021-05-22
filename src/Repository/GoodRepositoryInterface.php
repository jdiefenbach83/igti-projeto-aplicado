<?php

namespace App\Repository;

use App\Entity\Good;

interface GoodRepositoryInterface
{
    public function findAll(): array;
    public function save(Good $good): void;
    public function remove(Good $good): void;
}