<?php

namespace App\Service;

use App\Entity\Good;
use App\Repository\AssetRepositoryInterface;
use App\Repository\GoodRepositoryInterface;

class GoodService implements CalculationInterface
{
    private GoodRepositoryInterface $goodRepository;
    private AssetRepositoryInterface $assetRepository;

    public function __construct(
        GoodRepositoryInterface $goodRepository,
        AssetRepositoryInterface $assetRepository
    )
    {
        $this->goodRepository = $goodRepository;
        $this->assetRepository = $assetRepository;
    }

    public function process(): void
    {
        $this->goodRepository->startWorkUnit();
        $this->removeGood();
        $this->calculateGood();
        $this->goodRepository->endWorkUnit();
    }

    private function removeGood(): void
    {
        $goods = $this->goodRepository->findAll();

        foreach ($goods as $good){
            $this->goodRepository->remove($good);
        }
    }

    private function calculateGood(): void
    {
        $years = $this->goodRepository->findYearsToExtract();

        foreach ($years as $year) {
            $prePositions = $this->goodRepository->findPositionsToExtractGoods($year['year']);

            foreach ($prePositions as $prePosition) {
                $assetId = $prePosition['asset_id'];
                $sequence = $prePosition['sequence'];
                $good = $this->goodRepository->findGood($assetId, $sequence);

                if (empty($good)) {
                    continue;
                }

                $assetId = $good[0]['assetId'];
                $asset = $this->assetRepository->findById($assetId);

                $assetCode = $asset->getCode();
                $companyName = $good[0]['companyName'];
                $companyCNPJ = $good[0]['companyCNPJ'];
                $quantity = number_format((float)$good[0]['quantity'], 0, ',', '.');
                $brokerName = $good[0]['brokerName'];
                $brokerCNPJ = $good[0]['brokerCNPJ'];
                $price = number_format((float)$good[0]['price'], 2, ',', '.');
                $currentSituation = bcmul($good[0]['quantity'], $good[0]['price'], 6);

                $description = "Empresa: $companyName - Código da ação: $assetCode - Qtde: $quantity - Preço médio: R$ $price - Corretora: $brokerName - CNPJ: $brokerCNPJ";

                $newGood = new Good();
                $newGood
                    ->setAsset($asset)
                    ->setYear($year['year'])
                    ->setCnpj($companyCNPJ)
                    ->setDescription($description)
                    ->setSituationYearBefore(.0)
                    ->setSituationCurrentYear($currentSituation);

                $this->goodRepository->save($newGood);
            }
        }
    }

    public function getAll(): array
    {
        return $this->goodRepository->findAll();
    }
}