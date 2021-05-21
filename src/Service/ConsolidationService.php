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
        $years = $this->consolidationRepository->findYearsToConsolidate();
        $markets = Consolidation::getMarketTypes();
        $negotiations = Consolidation::getNegotiationTypes();

        foreach($years as $year)
        {
            foreach($markets as $market)
            {
                foreach ($negotiations as $negotiation)
                {
                    $accumulatedLoss = .0;

                    for ($month = 1; $month <= 12; $month++)
                    {
                        $summarizedPositions = $this->consolidationRepository
                            ->findConsolidatePositions(
                                $year['year'],
                                $month,
                                $market,
                                $negotiation
                            );

                        $consolidation = new Consolidation();
                        $consolidation
                            ->setNegotiationType($negotiation)
                            ->setMarketType($market)
                            ->setYear($year['year'])
                            ->setMonth($month)
                            ->setResult(.0)
                            ->setAccumulatedLoss($accumulatedLoss)
                            ->setCompesatedLoss(.0)
                            ->setBasisToIr(.0)
                            ->setAliquot(.0)
                            ->setIrrf(.0)
                            ->setAccumulatedIrrf(.0)
                            ->setCompesatedIrrf(.0)
                            ->setIrrfToPay(.0)
                            ->setIr(.0)
                            ->setIrToPay(.0);

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
                                ->setAccumulatedLoss($accumulatedLoss)
                                ->setCompesatedLoss($compesadatedLoss)
                                ->setBasisToIr($basisToIr);
                        }

                        $this->consolidationRepository->add($consolidation);
                    }
                }
            }
        }
    }
}
