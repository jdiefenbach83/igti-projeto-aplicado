<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Consolidation implements EntityInterface, JsonSerializable
{
    use Timestampable;

    public const ASSET_TYPE_STOCK = 'STOCK';
    public const ASSET_TYPE_FUTURE_CONTRACT = 'FUTURE_CONTRACT';

    public const NEGOTIATION_TYPE_NORMAL = 'NORMAL';
    public const NEGOTIATION_TYPE_DAYTRADE = 'DAYTRADE';

    private ?int $id;
    private int $year;
    private int $month;
    private string $assetType;
    private string $negotiationType;
    private float $totalBought;
    private float $totalBoughtCosts;
    private float $totalQuantitySold;
    private float $totalSold;
    private float $totalSoldCosts;
    private float $accumulatedLoss;
    private float $balance;
    private float $totalCosts;
    private float $compesatedLoss;
    private float $irrfCharged;
    private float $irrfCalculated;
    private float $irToPay;

    public static function getAssetTypes(): array
    {
        return [
            self::ASSET_TYPE_STOCK,
            self::ASSET_TYPE_FUTURE_CONTRACT,
        ];
    }

    public static function getNegotiationTypes(): array
    {
        return [
            self::NEGOTIATION_TYPE_NORMAL,
            self::NEGOTIATION_TYPE_DAYTRADE,
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return Consolidation
     */
    public function setYear(int $year): Consolidation
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @param int $month
     * @return Consolidation
     */
    public function setMonth(int $month): Consolidation
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssetType(): string
    {
        return $this->assetType;
    }

    /**
     * @param string $assetType
     * @return Consolidation
     */
    public function setAssetType(string $assetType): Consolidation
    {
        if (!in_array($assetType, self::getAssetTypes(), true)){
            throw new \InvalidArgumentException("Invalid asset type");
        }

        $this->assetType = $assetType;

        return $this;
    }

    /**
     * @return string
     */
    public function getNegotiationType(): string
    {
        return $this->negotiationType;
    }

    /**
     * @param string $negotiationType
     * @return Consolidation
     */
    public function setNegotiationType(string $negotiationType): Consolidation
    {
        $this->negotiationType = $negotiationType;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalBought(): float
    {
        return $this->totalBought;
    }

    /**
     * @param float $totalBought
     * @return Consolidation
     */
    public function setTotalBought(float $totalBought): Consolidation
    {
        $this->totalBought = $totalBought;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalBoughtCosts(): float
    {
        return $this->totalBoughtCosts;
    }

    /**
     * @param float $totalBoughtCosts
     * @return Consolidation
     */
    public function setTotalBoughtCosts(float $totalBoughtCosts): Consolidation
    {
        $this->totalBoughtCosts = $totalBoughtCosts;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalQuantitySold(): float
    {
        return $this->totalQuantitySold;
    }

    /**
     * @param float $totalQuantitySold
     * @return Consolidation
     */
    public function setTotalQuantitySold(float $totalQuantitySold): Consolidation
    {
        $this->totalQuantitySold = $totalQuantitySold;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalSold(): float
    {
        return $this->totalSold;
    }

    /**
     * @param float $totalSold
     * @return Consolidation
     */
    public function setTotalSold(float $totalSold): Consolidation
    {
        $this->totalSold = $totalSold;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalSoldCosts(): float
    {
        return $this->totalSoldCosts;
    }

    /**
     * @param float $totalSoldCosts
     * @return Consolidation
     */
    public function setTotalSoldCosts(float $totalSoldCosts): Consolidation
    {
        $this->totalSoldCosts = $totalSoldCosts;

        return $this;
    }

    /**
     * @return float
     */
    public function getAccumulatedLoss(): float
    {
        return $this->accumulatedLoss;
    }

    /**
     * @param float $accumulatedLoss
     * @return Consolidation
     */
    public function setAccumulatedLoss(float $accumulatedLoss): Consolidation
    {
        $this->accumulatedLoss = $accumulatedLoss;

        return $this;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return Consolidation
     */
    public function setBalance(float $balance): Consolidation
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalCosts(): float
    {
        return $this->totalCosts;
    }

    /**
     * @param float $totalCosts
     * @return Consolidation
     */
    public function setTotalCosts(float $totalCosts): Consolidation
    {
        $this->totalCosts = $totalCosts;

        return $this;
    }

    /**
     * @return float
     */
    public function getCompesatedLoss(): float
    {
        return $this->compesatedLoss;
    }

    /**
     * @param float $compesatedLoss
     * @return Consolidation
     */
    public function setCompesatedLoss(float $compesatedLoss): Consolidation
    {
        $this->compesatedLoss = $compesatedLoss;

        return $this;
    }

    /**
     * @return float
     */
    public function getIrrfCharged(): float
    {
        return $this->irrfCharged;
    }

    /**
     * @param float $irrfCharged
     * @return Consolidation
     */
    public function setIrrfCharged(float $irrfCharged): Consolidation
    {
        $this->irrfCharged = $irrfCharged;

        return $this;
    }

    /**
     * @return float
     */
    public function getIrrfCalculated(): float
    {
        return $this->irrfCalculated;
    }

    /**
     * @param float $irrfCalculated
     * @return Consolidation
     */
    public function setIrrfCalculated(float $irrfCalculated): Consolidation
    {
        $this->irrfCalculated = $irrfCalculated;

        return $this;
    }

    /**
     * @return float
     */
    public function getIrToPay(): float
    {
        return $this->irToPay;
    }

    /**
     * @param float $irToPay
     * @return Consolidation
     */
    public function setIrToPay(float $irToPay): Consolidation
    {
        $this->irToPay = $irToPay;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'year' => $this->year,
            'month' => $this->month,
            'asset_type' => $this->assetType,
            'negotiation_type' => $this->negotiationType,
            'total_bought' => $this->totalBought,
            'total_sold' => $this->totalSold,
            'accumulated_loss' => $this->accumulatedLoss,
            'balance' => $this->balance,
            'total_costs' => $this->totalCosts,
            'compesated_loss' => $this->compesatedLoss,
            'irrf_charged' => $this->irrfCharged,
            'irrf_calculated' => $this->irrfCalculated,
            'ir_to_pay' => $this->irToPay,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/consolidation/' . $this->id
                ],
            ]
        ];
    }
}