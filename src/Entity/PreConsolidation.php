<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;

class PreConsolidation implements EntityInterface
{
    use Timestampable;

    public const NEGOTIATION_TYPE_NORMAL = 'NORMAL';
    public const NEGOTIATION_TYPE_DAYTRADE = 'DAYTRADE';

    private ?int $id;
    private Asset $asset;
    private string $negotiationType;
    private int $year;
    private int $month;
    private float $result;

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
}