<?php

namespace App\Service;

use App\Entity\BrokerageNote;
use App\Entity\Operation;
use App\Entity\Position;
use App\Repository\BrokerageNoteRepositoryInterface;
use App\Repository\PositionRepositoryInterface;

class PositionService
{
    /**
     * @var PositionRepositoryInterface
     */
    private PositionRepositoryInterface $positionRepository;
    /**
     * @var BrokerageNoteRepositoryInterface
     */
    private BrokerageNoteRepositoryInterface $brokerageNoteRepository;

    /**
     * PositionService constructor.
     * @param PositionRepositoryInterface $positionRepository
     * @param BrokerageNoteRepositoryInterface $brokerageNoteRepository
     */
    public function __construct(
        PositionRepositoryInterface $positionRepository,
        BrokerageNoteRepositoryInterface $brokerageNoteRepository
    )
    {
        $this->positionRepository = $positionRepository;
        $this->brokerageNoteRepository = $brokerageNoteRepository;
    }

    public function getAll(): array
    {
        return $this->positionRepository->findAll();
    }

    public function process(): void
    {
        $this->removePositionsByOperation();
        $this->createPositions();
        $this->calculatePositions();
    }

    private function removePositionsByOperation(): void
    {
        $positions = $this->positionRepository->findAll();

        foreach ($positions as $position){
            $this->positionRepository->remove($position);
        }
    }

    private function createPositions(): void {
        $brokerageNotes = $this->brokerageNoteRepository->findAll();

        foreach ($brokerageNotes as $brokerageNote){
            $this->addPosition($brokerageNote);
        }
    }

    private function addPosition(BrokerageNote $brokerageNote): void
    {
        if (!$brokerageNote->hasOperationsCompleted())
        {
            return;
        }

        /** @var Operation $operation */
        foreach ($brokerageNote->getOperations() as $operation)
        {
            $unitPrice = bcdiv($operation->getTotal(), $operation->getQuantity(), 2);

            $position = new Position();
            $position
                ->setAsset($operation->getAsset())
                ->setOperation($operation)
                ->setSequence(0)
                ->setType($operation->getType())
                ->setDate($brokerageNote->getDate())
                ->setQuantity($operation->getQuantity())
                ->setUnitPrice($unitPrice)
                ->setTotalOperation($operation->getTotal())
                ->setTotalCosts($operation->getTotalCosts())
                ->setAccumulatedQuantity(0)
                ->setAccumulatedTotal(.0)
                ->setAccumulatedCosts(.0)
                ->setAveragePrice(.0)
                ->setAveragePriceToIr(.0);

            $this->positionRepository->add($position);
        }
    }

    private function calculatePositions(): void
    {
        $assetIds = $this->positionRepository->findAllAssets();

        foreach ($assetIds as $assetId)
        {
            $positions = $this->positionRepository->findByAsset($assetId['id']);

            $lastPositionAccumulatedQuantity = 0;
            $lastPositionAccumulatedTotal = 0;
            $lastPositionAccumulatedCosts = 0;
            $lastAveragePriceToIr = 0;

            /**
             * @var int $current
             * @var Position $currentValue */
            foreach ($positions as $current => $currentValue) {
                $position = $currentValue;

                $sequence = $current + 1;
                $position->setSequence($sequence);

                $averagePrice = bcadd($position->getTotalOperation(), $position->getTotalCosts(), 6);
                $averagePrice = bcdiv($averagePrice, $position->getQuantity(), 6);

                if ($sequence === 1) {
                    $accumulatedQuantity = $position->getQuantity();
                    $accumulatedTotal = $position->getTotalOperation();
                    $accumulatedCosts = $position->getTotalCosts();

                    $averagePriceToIr = $averagePrice;

                } else {
                    $signal = ($position->getType() === Position::TYPE_BUY) ? 1 : -1;

                    $accumulatedQuantity = bcadd(($position->getQuantity() * $signal), $lastPositionAccumulatedQuantity, 6);
                    $accumulatedTotal = bcadd(($position->getTotalOperation() * $signal), $lastPositionAccumulatedTotal, 6);
                    $accumulatedCosts = bcadd(($position->getTotalCosts() * $signal), $lastPositionAccumulatedCosts, 6);

                    if ($position->getType() === Position::TYPE_BUY) {
                        $averagePriceToIr = bcmul($lastPositionAccumulatedQuantity, $lastAveragePriceToIr, 6);
                        $totalLine = bcmul($averagePrice, $position->getQuantity(), 6);
                        $averagePriceToIr = bcadd($averagePriceToIr, $totalLine, 6);
                        $averagePriceToIr = bcdiv($averagePriceToIr, $accumulatedQuantity, 6);

                    } else {
                        $averagePriceToIr = $lastAveragePriceToIr;
                    }
                }

                $position->setAccumulatedQuantity($accumulatedQuantity);
                $position->setAccumulatedTotal($this->adjustDecimalValues($accumulatedTotal));
                $position->setAccumulatedCosts($this->adjustDecimalValues($accumulatedCosts));
                $position->setAveragePrice($this->adjustDecimalValues($averagePrice));
                $position->setAveragePriceToIr($this->adjustDecimalValues($averagePriceToIr));

                if ($sequence === count($positions)) {
                    $position->setIsLast(true);
                }

                $this->positionRepository->update($position);

                $lastPositionAccumulatedQuantity = $accumulatedQuantity;
                $lastPositionAccumulatedTotal = $accumulatedTotal;
                $lastPositionAccumulatedCosts = $accumulatedCosts;
                $lastAveragePriceToIr = $averagePriceToIr;
            }
        }
    }

    private function adjustDecimalValues(float $value): float
    {
        return (float) number_format($value, 4, '.', '');
    }
}