<?php

namespace App\Repository;

use App\Entity\PreConsolidation;

interface PreConsolidationRepositoryInterface
{
    public function findAll();
    public function findPreConsolidatePositions(): array;
    public function add(PreConsolidation $preConsolidation): void;
    public function remove(PreConsolidation $preConsolidation): void;
}