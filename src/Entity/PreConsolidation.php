<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;

class PreConsolidation implements EntityInterface
{
    use Timestampable;

    public const TYPE_BUY = 'BUY';
    public const TYPE_SELL = 'SELL';

    private ?int $id;
    private int $year;
    private int $month;
    private Asset $asset;
    private string $type;
    private int $quantity;
    private float $totalOperation;
    private float $totalCosts;

    public static function getTypes(): array
    {
        return [
            self::TYPE_BUY,
            self::TYPE_SELL,
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
     * @return PreConsolidation
     */
    public function setYear(int $year): PreConsolidation
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
     * @return PreConsolidation
     */
    public function setMonth(int $month): PreConsolidation
    {
        $this->month = $month;

        return $this;
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
     * @return PreConsolidation
     */
    public function setAsset(Asset $asset): PreConsolidation
    {
        $this->asset = $asset;

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
     * @return PreConsolidation
     */
    public function setType(string $type): PreConsolidation
    {
        $this->type = $type;

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
     * @return PreConsolidation
     */
    public function setQuantity(int $quantity): PreConsolidation
    {
        $this->quantity = $quantity;

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
     * @return PreConsolidation
     */
    public function setTotalOperation(float $totalOperation): PreConsolidation
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
     * @return PreConsolidation
     */
    public function setTotalCosts(float $totalCosts): PreConsolidation
    {
        $this->totalCosts = $totalCosts;

        return $this;
    }
}