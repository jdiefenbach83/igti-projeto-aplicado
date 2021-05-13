<?php

namespace App\Service;

use App\Entity\PreConsolidation;
use App\Repository\AssetRepositoryInterface;
use App\Repository\PreConsolidationRepositoryInterface;

class ConsolidationService implements CalculationInterface
{
    private PreConsolidationRepositoryInterface $preConsolidationRepository;
    private AssetRepositoryInterface $assetRepository;

    public function __construct(
        PreConsolidationRepositoryInterface $preConsolidationRepository,
        AssetRepositoryInterface $assetRepository
    )
    {
        $this->preConsolidationRepository = $preConsolidationRepository;
        $this->assetRepository = $assetRepository;
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