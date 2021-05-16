<?php

namespace App\Service;

use App\Entity\PreConsolidation;
use App\Repository\AssetRepositoryInterface;
use App\Repository\ConsolidationRepositoryInterface;
use App\Repository\PreConsolidationRepositoryInterface;

class PreConsolidationService implements CalculationInterface
{
    private AssetRepositoryInterface $assetRepository;
    private PreConsolidationRepositoryInterface $preConsolidationRepository;

    public function __construct(
        AssetRepositoryInterface $assetRepository,
        PreConsolidationRepositoryInterface $preConsolidationRepository
    )
    {
        $this->assetRepository = $assetRepository;
        $this->preConsolidationRepository = $preConsolidationRepository;
    }

    public function process(): void
    {
        $this->preConsolidationRepository->startWorkUnit();
        $this->removePreConsolidations();
        $this->calculatePreConsolidation();
        $this->preConsolidationRepository->endWorkUnit();
    }

    private function removePreConsolidations(): void
    {
        $preConsolidations = $this->preConsolidationRepository->findAll();

        foreach ($preConsolidations as $preConsolidation){
            $this->preConsolidationRepository->remove($preConsolidation);
        }
    }

    private function calculatePreConsolidation(): void
    {
        $summarizedPositions = $this->preConsolidationRepository->findPreConsolidatePositions();

        foreach ($summarizedPositions as $position) {
            $asset = $this->assetRepository->findById($position['assetId']);

            $preConsolidation = new PreConsolidation();
            $preConsolidation
                ->setAsset($asset)
                ->setNegotiationType($position['negotiationType'])
                ->setYear($position['year'])
                ->setMonth($position['month'])
                ->setResult($position['result']);

            $this->preConsolidationRepository->add($preConsolidation);
        }
    }
}
