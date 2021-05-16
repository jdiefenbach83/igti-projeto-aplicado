<?php

namespace App\Repository;

use App\Entity\Consolidation;

interface ConsolidationRepositoryInterface
{
    public function findAll(): array;
    public function findConsolidatePositions(): array;
    public function add(Consolidation $consolidation): void;
    public function remove(Consolidation $consolidation): void;
}