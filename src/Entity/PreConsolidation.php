<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;

class PreConsolidation implements EntityInterface
{
    use Timestampable;

    public const ASSET_TYPE_STOCK = 'STOCK';
    public const ASSET_TYPE_INDEX = 'INDEX';
    public const ASSET_TYPE_DOLAR = 'DOLAR';

    public const NEGOTIATION_TYPE_NORMAL = 'NORMAL';
    public const NEGOTIATION_TYPE_DAYTRADE = 'DAYTRADE';

    public const MARKET_TYPE_SPOT = 'SPOT';
    public const MARKET_TYPE_FUTURE = 'FUTURE';

    private ?int $id;
    private Asset $asset;
    private string $assetType;
    private string $negotiationType;
    private string $marketType;
    private int $year;
    private int $month;
    private float $result;
    private float $salesTotal;

    public static function getAssetTypes(): array
    {
        return [
            self::ASSET_TYPE_STOCK,
            self::ASSET_TYPE_INDEX,
            self::ASSET_TYPE_DOLAR,
        ];
    }

    public static function getNegotiationTypes(): array
    {
        return [
            self::NEGOTIATION_TYPE_NORMAL,
            self::NEGOTIATION_TYPE_DAYTRADE,
        ];
    }

    public static function getMarketTypes(): array
    {
        return [
            self::MARKET_TYPE_SPOT,
            self::MARKET_TYPE_FUTURE,
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
    public function getAssetType(): string
    {
        return $this->assetType;
    }

    /**
     * @param string $assetType
     * @return PreConsolidation
     */
    public function setAssetType(string $assetType): self
    {
        if (!in_array($assetType, self::getAssetTypes(), true)) {
            throw new \InvalidArgumentException('Invalid asset type');
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
     * @return PreConsolidation
     */
    public function setNegotiationType(string $negotiationType): PreConsolidation
    {
        if (!in_array($negotiationType, self::getNegotiationTypes(), true)){
            throw new \InvalidArgumentException("Invalid negotiation type");
        }

        $this->negotiationType = $negotiationType;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarketType(): string
    {
        return $this->marketType;
    }

    /**
     * @param string $marketType
     * @return PreConsolidation
     */
    public function setMarketType(string $marketType): PreConsolidation
    {
        if (!in_array($marketType, self::getMarketTypes(), true)){
            throw new \InvalidArgumentException("Invalid market type");
        }

        $this->marketType = $marketType;

        return $this;
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
     * @return float
     */
    public function getResult(): float
    {
        return $this->result;
    }

    /**
     * @param float $result
     * @return PreConsolidation
     */
    public function setResult(float $result): PreConsolidation
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return float
     */
    public function getSalesTotal(): float
    {
        return $this->salesTotal;
    }

    /**
     * @param float $salesTotal
     * @return PreConsolidation
     */
    public function setSalesTotal(float $salesTotal): PreConsolidation
    {
        $this->salesTotal = $salesTotal;

        return $this;
    }
}