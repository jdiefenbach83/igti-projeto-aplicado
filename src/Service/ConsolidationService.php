<?php

namespace App\Service;

use App\Entity\Asset;
use App\Entity\Consolidation;
use App\Entity\PreConsolidation;
use App\Repository\AssetRepositoryInterface;
use App\Repository\ConsolidationRepositoryInterface;
use App\Repository\PreConsolidationRepositoryInterface;

class ConsolidationService implements CalculationInterface
{
    private ConsolidationRepositoryInterface $consolidationRepository;

    public function __construct(
        ConsolidationRepositoryInterface $consolidationRepository
    )
    {
        $this->consolidationRepository = $consolidationRepository;
    }

    public function process(): void
    {
        $this->consolidationRepository->startWorkUnit();
        $this->removeConsolidations();
        $this->calculateConsolidation();
        $this->consolidationRepository->endWorkUnit();
    }

    private function removeConsolidations(): void
    {
        $consolidations = $this->consolidationRepository->findAll();

        foreach ($consolidations as $consolidation){
            $this->consolidationRepository->remove($consolidation);
        }
    }

    private function calculateConsolidation(): void
    {
        $summarizedPositions = $this->consolidationRepository->findConsolidatePositions();

        foreach ($summarizedPositions as $position) {

            $marketType = $this->mapConsolitationToMarketType($position['assetType']);

            $consolidation = new Consolidation();
            $consolidation
                ->setNegotiationType($position['negotiationType'])
                ->setMarketType($marketType)
                ->setYear($position['year'])
                ->setMonth($position['month'])
                ->setResult($position['result'])
                ->setAccumulatedLoss(.0)
                ->setCompesatedLoss(.0)
                ->setBasisToIr(.0)
                ->setAliquot(.0)
                ->setIrrf(.0)
                ->setIrToPay(.0);

            $this->consolidationRepository->add($consolidation);
        }
    }

    private function mapConsolitationToMarketType(string $consolidationType): ?string
    {
        $map[Asset::TYPE_STOCK] = Consolidation::MARKET_TYPE_SPOT;
        $map[Asset::TYPE_FUTURE_CONTRACT] = Consolidation::MARKET_TYPE_FUTURE;

        return $map[$consolidationType];
    }
}
