<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;

class PreConsolidation implements EntityInterface
{
    use Timestampable;

    public const NEGOTIATION_TYPE_NORMAL = 'NORMAL';
    public const NEGOTIATION_TYPE_DAYTRADE = 'DAYTRADE';

    private ?int $id;
    private int $year;
    private int $month;
    private Asset $asset;
    private string $negotiationType;
    private float $result;
    private float $negativeResultLastMonth;
    private float $calculationBasis;
    private float $lossToCompensate;
    private float $withholdingTax;
    private float $taxRate;
    private float $taxDue;

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
    public function getNegativeResultLastMonth(): float
    {
        return $this->negativeResultLastMonth;
    }

    /**
     * @param float $negativeResultLastMonth
     * @return PreConsolidation
     */
    public function setNegativeResultLastMonth(float $negativeResultLastMonth): PreConsolidation
    {
        $this->negativeResultLastMonth = $negativeResultLastMonth;

        return $this;
    }

    /**
     * @return float
     */
    public function getCalculationBasis(): float
    {
        return $this->calculationBasis;
    }

    /**
     * @param float $calculationBasis
     * @return PreConsolidation
     */
    public function setCalculationBasis(float $calculationBasis): PreConsolidation
    {
        $this->calculationBasis = $calculationBasis;

        return $this;
    }

    /**
     * @return float
     */
    public function getLossToCompensate(): float
    {
        return $this->lossToCompensate;
    }

    /**
     * @param float $lossToCompensate
     * @return PreConsolidation
     */
    public function setLossToCompensate(float $lossToCompensate): PreConsolidation
    {
        $this->lossToCompensate = $lossToCompensate;

        return $this;
    }

    /**
     * @return float
     */
    public function getWithholdingTax(): float
    {
        return $this->withholdingTax;
    }

    /**
     * @param float $withholdingTax
     * @return PreConsolidation
     */
    public function setWithholdingTax(float $withholdingTax): PreConsolidation
    {
        $this->withholdingTax = $withholdingTax;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    /**
     * @param float $taxRate
     * @return PreConsolidation
     */
    public function setTaxRate(float $taxRate): PreConsolidation
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxDue(): float
    {
        return $this->taxDue;
    }

    /**
     * @param float $taxDue
     * @return PreConsolidation
     */
    public function setTaxDue(float $taxDue): PreConsolidation
    {
        $this->taxDue = $taxDue;

        return $this;
    }
}