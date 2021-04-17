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
        $this->processNegotiations();
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
                ->setNegotiationType(Position::NEGOTIATION_TYPE_NORMAL)
                ->setDate($brokerageNote->getDate())
                ->setQuantity($operation->getQuantity())
                ->setUnitPrice($unitPrice)
                ->setTotalOperation($operation->getTotal())
                ->setTotalCosts($operation->getTotalCosts())
                ->setPositionPrice(.0)
                ->setAccumulatedQuantity(0)
                ->setAccumulatedTotal(.0)
                ->setAccumulatedCosts(.0)
                ->setAveragePrice(.0)
                ->setAveragePriceToIr(.0)
                ->setQuantityBalance($operation->getQuantity());

            $this->positionRepository->add($position);
        }
    }

    private function processNegotiations(): void
    {
        $daytrades = $this->positionRepository->findDayTradeNegotiations();

        foreach($daytrades as $daytrade){
            $quantityBuy = $daytrade['quantity_buy'];
            $quantitySell = $daytrade['quantity_sell'];

            $this->processDaytradePositions($quantityBuy, $quantitySell, $daytrade['asset_id'], $daytrade['date'], Position::TYPE_BUY);
            $this->processDaytradePositions($quantityBuy, $quantitySell, $daytrade['asset_id'], $daytrade['date'], Position::TYPE_SELL);
        }
    }

    private function processDaytradePositions(int $quantityBuy, int $quantitySell, int $assetId, \DateTimeImmutable $date, string $type): void
    {
        $balance = (int) bcsub($quantityBuy, $quantitySell);

        $daytradeQuantity = ($quantityBuy > $quantitySell) ? $quantityBuy : $quantitySell;
        $daytradeQuantity = abs(bcsub($daytradeQuantity, $balance));

        $positions = $this->positionRepository->findByAssetAndDateAndType($assetId, $date, $type);

        do {
            /** @var Position $position */
            foreach ($positions as $position) {
                $quantity = $position->getQuantity();

                if ($position->getNegotiationType() === Position::NEGOTIATION_TYPE_DAYTRADE) {
                    $daytradeQuantity -= $quantity;

                    continue;
                }

                $balance = 0;

                if ($quantity > $daytradeQuantity) {
                    $balance = $quantity - $daytradeQuantity;
                }

                $position->setNegotiationType(Position::NEGOTIATION_TYPE_DAYTRADE);
                $position->setQuantityBalance($balance);
                $this->positionRepository->update($position);

                $daytradeQuantity -= $quantity;
            }
        } while ($daytradeQuantity > 0);
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
            $lastAveragePrice = 0;
            $lastAveragePriceToIr = 0;

            /**
             * @var int $current
             * @var Position $currentValue */
            foreach ($positions as $current => $currentValue) {
                $position = $currentValue;

                $sequence = $current + 1;
                $position->setSequence($sequence);

                $signal = ($position->getType() === Position::TYPE_BUY) ? 1 : -1;

                $positionPrice = bcadd($position->getTotalOperation(), ($position->getTotalCosts() * $signal), 6);
                $positionPrice = bcdiv($positionPrice, $position->getQuantity(), 6);

                if ($sequence === 1) {
                    $accumulatedQuantity = $position->getQuantity();
                    $accumulatedTotal = $position->getTotalOperation();
                    $accumulatedCosts = $position->getTotalCosts();

                    $averagePrice = $positionPrice;
                    $averagePriceToIr = $positionPrice;

                } else {
                    $accumulatedQuantity = bcadd(($position->getQuantity() * $signal), $lastPositionAccumulatedQuantity, 6);

                    $accumulatedTotal = .0;
                    $accumulatedCosts = .0;

                    if ($accumulatedQuantity > 0) {
                        $accumulatedTotal = bcadd(($position->getTotalOperation() * $signal), $lastPositionAccumulatedTotal, 6);
                        $accumulatedCosts = bcadd(($position->getTotalCosts() * $signal), $lastPositionAccumulatedCosts, 6);
                    }

                    if ($position->getType() === Position::TYPE_BUY) {
                        $averagePrice = bcadd($accumulatedTotal, $accumulatedCosts, 6);
                        $averagePrice = bcdiv($averagePrice, $accumulatedQuantity, 6);

                        $totalLine = bcmul($positionPrice, $position->getQuantity(), 6);

                        $averagePriceToIr = bcmul($lastPositionAccumulatedQuantity, $lastAveragePriceToIr, 6);
                        $averagePriceToIr = bcadd($averagePriceToIr, $totalLine, 6);
                        $averagePriceToIr = bcdiv($averagePriceToIr, $accumulatedQuantity, 6);

                    } else {
                        $averagePrice = $lastAveragePrice;
                        $averagePriceToIr = $lastAveragePriceToIr;
                    }
                }

                $position->setPositionPrice($this->adjustDecimalValues($positionPrice));
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
                $lastAveragePrice = $averagePrice;
                $lastAveragePriceToIr = $averagePriceToIr;
            }
        }
    }

    private function adjustDecimalValues(float $value): float
    {
        return (float) number_format($value, 4, '.', '');
    }
}