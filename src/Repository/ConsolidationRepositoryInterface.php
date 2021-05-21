<?php

namespace App\Repository;

use App\Entity\Consolidation;

interface ConsolidationRepositoryInterface
{
    public function findAll(): array;
    public function findYearsToConsolidate(): array;
    public function findConsolidatePositions(int $year, int $month, string $market, string $negotiation): array;
    public function save(Consolidation $consolidation): void;
    public function remove(Consolidation $consolidation): void;
}