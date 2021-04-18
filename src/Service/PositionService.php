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
        $this->positionRepository->startWorkUnit();
        $this->removePositionsByOperation();
        $this->createPositions();
        $this->positionRepository->endWorkUnit();

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
                ->setResult(.0)
                ->setAccumulatedResult(.0)
                ->setQuantityBalance($operation->getQuantity());

            $this->positionRepository->add($position);
        }
    }

    private function processNegotiations(): void
    {
        $this->positionRepository->startWorkUnit();

        $dayTrades = $this->positionRepository->findDayTradeNegotiations();

        foreach($dayTrades as $dayTrade){
            $quantityBuy = $dayTrade['quantity_buy'];
            $quantitySell = $dayTrade['quantity_sell'];

            $this->processDayTradePositions($quantityBuy, $quantitySell, $dayTrade['asset_id'], $dayTrade['date'], Position::TYPE_BUY);
            $this->processDayTradePositions($quantityBuy, $quantitySell, $dayTrade['asset_id'], $dayTrade['date'], Position::TYPE_SELL);
        }

        $normalTrades = $this->positionRepository->findNormalNegotiations();

        foreach($normalTrades as $normalTrade){
            $quantityBuy = $normalTrade['quantity_buy'];
            $quantitySell = $normalTrade['quantity_sell'];

            $this->processNormalTradePositions($quantityBuy, $quantitySell, $normalTrade['asset_id'], Position::TYPE_BUY);
            $this->processNormalTradePositions($quantityBuy, $quantitySell, $normalTrade['asset_id'], Position::TYPE_SELL);
        }

        $this->positionRepository->endWorkUnit();

        $this->processDayTradesWithBalance();
    }

    private function processDayTradePositions(int $quantityBuy, int $quantitySell, int $assetId, \DateTimeImmutable $date, string $type): void
    {
        $result = (int) bcsub($quantityBuy, $quantitySell);

        $dayTradeQuantity = ($quantityBuy > $quantitySell) ? $quantityBuy : $quantitySell;
        $dayTradeQuantity = abs(bcsub($dayTradeQuantity, $result));

        $positions = $this->positionRepository->findByAssetAndTypeAndDate($assetId, $type, $date);

        do {
            /** @var Position $position */
            foreach ($positions as $position) {
                $quantity = $position->getQuantity();

                if ($position->getNegotiationType() === Position::NEGOTIATION_TYPE_DAYTRADE) {
                    $dayTradeQuantity -= $quantity;

                    continue;
                }

                $balance = 0;
                $controlBalance = $quantity;

                if ($quantity > $dayTradeQuantity) {
                    $balance = $quantity - $dayTradeQuantity;
                    $controlBalance = $dayTradeQuantity;
                }

                $position->setNegotiationType(Position::NEGOTIATION_TYPE_DAYTRADE);
                $position->setQuantityBalance($balance);
                $this->positionRepository->update($position);

                $dayTradeQuantity -= $controlBalance;
            }
        } while ($dayTradeQuantity > 0);
    }

    private function processNormalTradePositions(int $quantityBuy, int $quantitySell, int $assetId, string $type): void
    {
        $result = (int) bcsub($quantityBuy, $quantitySell);

        $normalTradeQuantity = ($quantityBuy > $quantitySell) ? $quantityBuy : $quantitySell;
        $normalTradeQuantity = abs(bcsub($normalTradeQuantity, $result));

        $positions = $this->positionRepository->findByAssetAndTypeAndDate($assetId, $type);

        do {
            /** @var Position $position */
            foreach ($positions as $position) {
                $quantity = $position->getQuantity();

                $newBalance = 0;
                $controlBalance = $quantity;

                if ($quantity > $normalTradeQuantity) {
                    $newBalance = $quantity - $normalTradeQuantity;
                    $controlBalance = $normalTradeQuantity;
                }

                $position->setQuantityBalance($newBalance);
                $this->positionRepository->update($position);

                $normalTradeQuantity -= $controlBalance;
            }
        } while ($normalTradeQuantity > 0);
    }

    private function processDayTradesWithBalance(): void
    {
        $processDayTradesWithBalance = $this->positionRepository->findDayTradeNegotiationsWithBalance();

        $this->positionRepository->startWorkUnit();

        /** @var Position $position */
        foreach ($processDayTradesWithBalance as $position)
        {
            if ($position->getQuantity() === $position->getQuantityBalance()) {
                $position->setNegotiationType(Position::NEGOTIATION_TYPE_NORMAL);
                $this->positionRepository->update($position);
            }
        }

        $this->positionRepository->endWorkUnit();
    }

    private function calculatePositions(): void
    {
        $this->positionRepository->startWorkUnit();

        $assetIds = $this->positionRepository->findAllAssets();

        foreach ($assetIds as $assetId)
        {
            $positions = $this->positionRepository->findByAsset($assetId['id']);

            $lastPositionAccumulatedQuantity = 0;
            $lastPositionAccumulatedTotal = 0;
            $lastPositionAccumulatedCosts = 0;
            $lastAveragePrice = 0;
            $lastAveragePriceToIr = 0;
            $lastPositionAccumulatedResult = 0;

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

                $result = .0;
                $accumulatedResult = .0;

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

                        $result = bcsub($positionPrice, $averagePriceToIr, 4);
                        $result = bcmul($result, $position->getQuantity(), 6);
                    }

                    $accumulatedResult = bcadd($lastPositionAccumulatedResult, $result, 4);
                }

                $position->setPositionPrice($this->adjustDecimalValues($positionPrice));
                $position->setAccumulatedQuantity($accumulatedQuantity);
                $position->setAccumulatedTotal($this->adjustDecimalValues($accumulatedTotal));
                $position->setAccumulatedCosts($this->adjustDecimalValues($accumulatedCosts));
                $position->setAveragePrice($this->adjustDecimalValues($averagePrice));
                $position->setAveragePriceToIr($this->adjustDecimalValues($averagePriceToIr));
                $position->setResult($result);
                $position->setAccumulatedResult($accumulatedResult);

                if ($sequence === count($positions)) {
                    $position->setIsLast(true);
                }

                $this->positionRepository->update($position);

                $lastPositionAccumulatedQuantity = $accumulatedQuantity;
                $lastPositionAccumulatedTotal = $accumulatedTotal;
                $lastPositionAccumulatedCosts = $accumulatedCosts;
                $lastAveragePrice = $averagePrice;
                $lastAveragePriceToIr = $averagePriceToIr;
                $lastPositionAccumulatedResult = $accumulatedResult;
            }
        }

        $this->positionRepository->endWorkUnit();
    }

    private function adjustDecimalValues(float $value): float
    {
        return (float) number_format($value, 4, '.', '');
    }
}