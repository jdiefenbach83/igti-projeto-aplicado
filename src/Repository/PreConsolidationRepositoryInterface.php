<?php

namespace App\Repository;

use App\Entity\PreConsolidation;

interface PreConsolidationRepositoryInterface extends WorkUnitInterface
{
    public function findAll();
    public function findPreConsolidatePositions(): array;
    public function save(PreConsolidation $preConsolidation): void;
    public function remove(PreConsolidation $preConsolidation): void;
}