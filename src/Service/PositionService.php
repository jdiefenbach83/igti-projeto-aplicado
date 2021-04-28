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
        $dayTrades = $this->positionRepository->findDayTradeNegotiations();

        foreach($dayTrades as $dayTrade){
            $this->processDaytradeNegotiations($dayTrade['asset_id'], $dayTrade['date']);
        }

        $normalTrades = $this->positionRepository->findNormalNegotiations();

        foreach($normalTrades as $normalTrade){
            $this->processNormalTradeNegotiations($normalTrade['asset_id']);
        }
    }

    private function processDaytradeNegotiations(int $assetId, \DateTimeImmutable $date): void
    {
        $negotiationType = Position::NEGOTIATION_TYPE_DAYTRADE;

        $buys = $this->positionRepository->findByAssetAndTypeAndDateAndNegotiationType($assetId, Position::TYPE_BUY, $negotiationType, $date);
        $sells = $this->positionRepository->findByAssetAndTypeAndDateAndNegotiationType($assetId, Position::TYPE_SELL, $negotiationType, $date);

        $this->positionRepository->startWorkUnit();

        $processDaytradeWithBalance = $this->processZeroBalanceDaytradePositions($buys, $sells) === false;

        if ($processDaytradeWithBalance) {
            $this->processPositions($buys, $sells, $negotiationType);
        }

        $this->positionRepository->endWorkUnit();
    }

    private function processZeroBalanceDaytradePositions(array $buys, array $sells): bool
    {
        $sumarizedQuantities = $this->summarizeQuantities($buys, $sells);
        $zeroQuantity = $sumarizedQuantities['buys'] - $sumarizedQuantities['sells'];

        if ($zeroQuantity !== 0) {
            return false;
        }

        $positions = array_merge($buys, $sells);

        /** @var Position $position */
        foreach ($positions as $position){
             $position
                ->setNegotiationType(Position::NEGOTIATION_TYPE_DAYTRADE)
                ->setQuantityBalance(0);

            $this->positionRepository->update($position);
        }

        return true;
    }

    private function summarizeQuantities(array $buys, array $sells): array
    {
        $buysQuantity = array_reduce($buys, static function ($accumulator, $position) {
            return $accumulator + $position->getQuantity();
        }, 0);

        $sellsQuantity = array_reduce($sells, static function ($accumulator, $position) {
            return $accumulator + $position->getQuantity();
        }, 0);

        return [
            'buys' => $buysQuantity,
            'sells' => $sellsQuantity,
        ];
    }

    private function processNormalTradeNegotiations(int $assetId): void
    {
        $negotiationType = Position::NEGOTIATION_TYPE_NORMAL;

        $buys = $this->positionRepository->findByAssetAndTypeAndDateAndNegotiationType($assetId, Position::TYPE_BUY, $negotiationType);
        $sells = $this->positionRepository->findByAssetAndTypeAndDateAndNegotiationType($assetId, Position::TYPE_SELL, $negotiationType);

        $this->positionRepository->startWorkUnit();

        $this->processPositions($buys, $sells, $negotiationType);
        $this->transformDayTradeWithBalanceIntoNormalTrade();

        $this->positionRepository->endWorkUnit();
    }

    private function processPositions(array $buys, array $sells, string $negotiationType): void
    {
        $sumarizedQuantities = $this->summarizeQuantities($buys, $sells);
        $isShortSale = bcsub($sumarizedQuantities['sells'], $sumarizedQuantities['buys']);
        $isShortSale = $isShortSale > 0;

        if ($isShortSale){
            throw new \Exception('Short sale position calculation not implemented');
        }

        $operators = [
            'equals',
            'greater'
        ];

        /** @var Position $sell */
        foreach ($sells as $sell) {
            $soldQuantity = $sell->getQuantity();

            do {
                foreach ($operators as $operator) {
                    if ($soldQuantity === 0) {
                        break;
                    }

                    $soldQuantity = $this->decreaseSellFromBuys($buys, $operator, $negotiationType, $soldQuantity);
                }
            } while ($soldQuantity > 0);

            $sell
                ->setNegotiationType($negotiationType)
                ->setQuantityBalance(.0);
            $this->positionRepository->update($sell);
        }
    }

    private function decreaseSellFromBuys(array $buys, string $operator, string $negotiationType, int $soldQuantity): int
    {
        $isEqualsOperator = $operator === 'equals';
        $isGreaterThanOperator = $operator === 'greater';

        /** @var Position $buy */
        foreach ($buys as $buy) {
            if ($buy->getQuantityBalance() === 0) {
                continue;
            }

            $boughtQuantity = $buy->getQuantity();

            if ($isEqualsOperator && ($boughtQuantity === $soldQuantity)) {
                $balance = 0;
                $controlBalance = $boughtQuantity;
            } elseif ($isGreaterThanOperator && ($boughtQuantity > $soldQuantity)) {
                $balance = $boughtQuantity - $soldQuantity;
                $controlBalance = $soldQuantity;
            } elseif ($isGreaterThanOperator && ($soldQuantity > $boughtQuantity)) {
                $balance = 0;
                $controlBalance = $boughtQuantity;
            } else {
                continue;
            }

            $buy
                ->setNegotiationType($negotiationType)
                ->setQuantityBalance($balance);
            $this->positionRepository->update($buy);

            $soldQuantity -= $controlBalance;
            break;
        }

        return $soldQuantity;
    }

    private function transformDayTradeWithBalanceIntoNormalTrade(): void
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

                        $totalLine = bcmul($positionPrice, $position->getQuantity(), 6);

                        $averagePriceToIr = bcmul($lastPositionAccumulatedQuantity, $lastAveragePriceToIr, 6);
                        $averagePriceToIr = bcadd($averagePriceToIr, $totalLine, 6);

                        if ($accumulatedQuantity > 0) {
                            $averagePrice = bcdiv($averagePrice, $accumulatedQuantity, 6);
                            $averagePriceToIr = bcdiv($averagePriceToIr, $accumulatedQuantity, 6);
                        } else {
                            $averagePrice = .0;
                            $averagePriceToIr = .0;
                        }
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