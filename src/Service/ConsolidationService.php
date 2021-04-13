<?php

namespace App\Service;

use App\Entity\Asset;
use App\Entity\Position;
use App\Entity\PreConsolidation;
use App\Repository\PositionRepositoryInterface;

class ConsolidationService implements CalculationInterface
{
    /**
     * @var PositionRepositoryInterface
     */
    private PositionRepositoryInterface $positionRepository;
    private array $preConsolidations = [];

    public function __construct(PositionRepositoryInterface $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function process(): void
    {
        $this->calculatePreConsolidation();
    }

    private function calculatePreConsolidation(): void
    {
        $positions = $this->positionRepository->findAll();

        /** @var Position $position */
        foreach($positions as $position){
            $year = $position->getDate()->format('Y');
            $month = $position->getDate()->format('m');
            $asset = $position->getAsset();
            $type = $position->getType();

            $preConsolidation = $this->getOrBuildPreConsolidation($year, $month, $asset, $type);

            $quantity = bcadd($preConsolidation->getQuantity(), $position->getQuantity(), 4);
            $totalOperations = bcadd($preConsolidation->getTotalOperation(), $position->getTotalOperation(), 4);
            $totalCosts = bcadd($preConsolidation->getTotalCosts(), $position->getTotalCosts(), 4);

            $preConsolidation->setQuantity($quantity);
            $preConsolidation->setTotalOperation($totalOperations);
            $preConsolidation->setTotalCosts($totalCosts);

            $this->addPreConsolidation($preConsolidation);
        }
    }

    private function getOrBuildPreConsolidation(int $year, int $month, Asset $asset, string $type): PreConsolidation
    {
        /** @var PreConsolidation $filteredPreConsolidation */
        $filteredPreConsolidation = array_filter($this->preConsolidations, static function($preConsolidation) use ($year, $month, $asset, $type){
            return
                $preConsolidation->getAsset()->getId() === $asset->getId() &&
                $preConsolidation->getYear() === $year &&
                $preConsolidation->getMonth() === $month &&
                $preConsolidation->getType() === $type;
        });

        return $filteredPreConsolidation[0] ?? (new PreConsolidation())
            ->setYear($year)
            ->setMonth($month)
            ->setAsset($asset)
            ->setType($type)
            ->setQuantity(0)
            ->setTotalOperation(.0)
            ->setTotalCosts(.0);
    }

    private function addPreConsolidation(PreConsolidation $preConsolidation): void
    {
        /** @var PreConsolidation $filteredPreConsolidation */
        $filteredPreConsolidation = array_filter($this->preConsolidations, static function($item) use ($preConsolidation){
            return $preConsolidation->getAsset()->getId() !== $item->getAsset()->getId() ||
                $preConsolidation->getYear() !== $item->getYear() ||
                $preConsolidation->getMonth() !== $item->getMonth() ||
                $preConsolidation->getType() !== $item->getType();
        });

        $this->preConsolidations = array_merge($filteredPreConsolidation, [$preConsolidation]);
    }
}