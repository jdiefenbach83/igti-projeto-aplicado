<?php

namespace App\Entity;

use DateTimeImmutable;
use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Position implements EntityInterface, JsonSerializable
{
    use Timestampable;

    public const TYPE_BUY = 'BUY';
    public const TYPE_SELL = 'SELL';

    private ?int $id;
    private Asset $asset;
    private int $sequence;
    private string $type;
    private DateTimeImmutable $date;
    private int $quantity;
    private float $unitPrice;
    private int $accumulatedQuantity;
    private float $totalOperation;
    private float $totalCosts;
    private float $accumulatedTotal;
    private float $accumulatedCosts;
    private float $averagePrice;
    private float $averagePriceToIr;
    private ?Operation $operation;
    private bool $isLast;

    public static function getTypes(): array
    {
        return [
            self::TYPE_BUY,
            self::TYPE_SELL,
        ];
    }

    public function __construct()
    {
        $this->operation = null;
        $this->isLast = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * @param Asset $asset
     * @return Position
     */
    public function setAsset(Asset $asset): Position
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     * @return Position
     */
    public function setSequence(int $sequence): Position
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Position
     */
    public function setType(string $type): Position
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return Position
     */
    public function setDate(DateTimeImmutable $date): Position
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Position
     */
    public function setQuantity(int $quantity): Position
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     * @return Position
     */
    public function setUnitPrice(float $unitPrice): Position
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccumulatedQuantity(): int
    {
        return $this->accumulatedQuantity;
    }

    /**
     * @param int $accumulatedQuantity
     * @return Position
     */
    public function setAccumulatedQuantity(int $accumulatedQuantity): Position
    {
        $this->accumulatedQuantity = $accumulatedQuantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalOperation(): float
    {
        return $this->totalOperation;
    }

    /**
     * @param float $totalOperation
     * @return Position
     */
    public function setTotalOperation(float $totalOperation): Position
    {
        $this->totalOperation = $totalOperation;

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
     * @return Position
     */
    public function setTotalCosts(float $totalCosts): Position
    {
        $this->totalCosts = $totalCosts;
        return $this;
    }

    /**
     * @return float
     */
    public function getAccumulatedTotal(): float
    {
        return $this->accumulatedTotal;
    }

    /**
     * @param float $accumulatedTotal
     * @return Position
     */
    public function setAccumulatedTotal(float $accumulatedTotal): Position
    {
        $this->accumulatedTotal = $accumulatedTotal;

        return $this;
    }

    /**
     * @return float
     */
    public function getAccumulatedCosts(): float
    {
        return $this->accumulatedCosts;
    }

    /**
     * @param float $accumulatedCosts
     * @return Position
     */
    public function setAccumulatedCosts(float $accumulatedCosts): Position
    {
        $this->accumulatedCosts = $accumulatedCosts;
        return $this;
    }

    /**
     * @return float
     */
    public function getAveragePrice(): float
    {
        return $this->averagePrice;
    }

    /**
     * @param float $averagePrice
     * @return Position
     */
    public function setAveragePrice(float $averagePrice): Position
    {
        $this->averagePrice = $averagePrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getAveragePriceToIr(): float
    {
        return $this->averagePriceToIr;
    }

    /**
     * @param float $averagePriceToIr
     * @return Position
     */
    public function setAveragePriceToIr(float $averagePriceToIr): Position
    {
        $this->averagePriceToIr = $averagePriceToIr;
        return $this;
    }

    /**
     * @return Operation|null
     */
    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    /**
     * @param Operation $operation
     * @return Position
     */
    public function setOperation(Operation $operation): Position
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLast(): bool
    {
        return $this->isLast;
    }

    /**
     * @param bool $isLast
     * @return Position
     */
    public function setIsLast(bool $isLast): Position
    {
        $this->isLast = $isLast;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'asset_id' => $this->asset->getId(),
            'sequence' => $this->sequence,
            'type' => $this->type,
            'date' => $this->date->format('Y-m-d'),
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'total_operation' => $this->totalOperation,
            'total_costs' => $this->totalCosts,
            'accumulated_quantity' => $this->accumulatedQuantity,
            'accumulated_total' => $this->accumulatedTotal,
            'accumulated_costs' => $this->accumulatedCosts,
            'average_price' => $this->averagePrice,
            'average_price_to_ir' => $this->averagePriceToIr,
            'is_last' => $this->isLast,
        ];
    }
}