<?php

namespace App\Service;

use App\Entity\Asset;
use App\Entity\Consolidation;
use App\Entity\PreConsolidation;
use App\Repository\AssetRepositoryInterface;
use App\Repository\ConsolidationRepositoryInterface;
use App\Repository\PreConsolidationRepositoryInterface;
use App\Service\IrCalculator\IrCalculatorFactory;

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
        $years = $this->consolidationRepository->findYearsToConsolidate();
        $markets = Consolidation::getMarketTypes();
        $assets = Consolidation::getAssetTypes();
        $negotiations = Consolidation::getNegotiationTypes();

        foreach($years as $year)
        {
            foreach($markets as $market)
            {
                foreach ($negotiations as $negotiation)
                {
                    $accumulatedLoss = .0;

                    foreach($assets as $asset)
                    {

                        for ($month = 1; $month <= 12; $month++) {
                            $summarizedPositions = $this->consolidationRepository
                                ->findConsolidatePositions(
                                    $year['year'],
                                    $month,
                                    $market,
                                    $negotiation,
                                    $asset
                                );

                            $consolidation = new Consolidation();
                            $consolidation
                                ->setAssetType($asset)
                                ->setNegotiationType($negotiation)
                                ->setMarketType($market)
                                ->setYear($year['year'])
                                ->setMonth($month)
                                ->setResult(.0)
                                ->setSalesTotal(.0)
                                ->setAccumulatedLoss($accumulatedLoss)
                                ->setCompesatedLoss(.0)
                                ->setBasisToIr(.0)
                                ->setAliquot(.0)
                                ->setIrrf(.0)
                                ->setAccumulatedIrrf(.0)
                                ->setCompesatedIrrf(.0)
                                ->setIrrfToPay(.0)
                                ->setIr(.0)
                                ->setIrToPay(.0)
                                ->setIsExempt(false);

                            foreach ($summarizedPositions as $position) {
                                $result = (float)$position['result'];
                                $compesadatedLoss = .0;
                                $basisToIr = $result;

                                if ($result < .0) {
                                    $accumulatedLoss += $result * -1;
                                }

                                if ($result > .0 && $accumulatedLoss > .0) {
                                    if ($result > $accumulatedLoss) {
                                        $compesadatedLoss = $accumulatedLoss;
                                        $accumulatedLoss = .0;
                                        $basisToIr = $result - $compesadatedLoss;
                                    } elseif ($result === $accumulatedLoss) {
                                        $compesadatedLoss = .0;
                                        $accumulatedLoss = .0;
                                        $basisToIr = .0;
                                    } else {
                                        $compesadatedLoss = $accumulatedLoss - $result;
                                        $accumulatedLoss -= $compesadatedLoss;
                                        $basisToIr = .0;
                                    }
                                }

                                $consolidation
                                    ->setResult($result)
                                    ->setSalesTotal($position['salesTotal'])
                                    ->setAccumulatedLoss($accumulatedLoss)
                                    ->setCompesatedLoss($compesadatedLoss)
                                    ->setBasisToIr($basisToIr);

                                if ($basisToIr > .0) {
                                    $calculator = IrCalculatorFactory::getCalculatorMethod($consolidation);

                                    $aliquot = $calculator->getAliquot();
                                    $irrf = $calculator->calculateIrrf();
                                    $ir = $calculator->calculateIr();
                                    $irToPay = bcsub($ir, $irrf, 4);
                                    $isExempt = $calculator->isExempt();

                                    $consolidation
                                        ->setAliquot($aliquot)
                                        ->setIrrf($irrf)
                                        ->setIrrfToPay($irrf)
                                        ->setIr($ir)
                                        ->setIrToPay($irToPay)
                                        ->setIsExempt($isExempt);
                                }
                            }

                            $this->consolidationRepository->save($consolidation);
                        }
                    }
                }
            }
        }
    }

    public function getAll(): array
    {
        return $this->consolidationRepository->findAll();
    }
}
