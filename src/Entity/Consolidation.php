<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Consolidation implements EntityInterface, JsonSerializable
{
    use Timestampable;

    public const NEGOTIATION_TYPE_NORMAL = 'NORMAL';
    public const NEGOTIATION_TYPE_DAYTRADE = 'DAYTRADE';

    public const MARKET_TYPE_SPOT = 'SPOT';
    public const MARKET_TYPE_FUTURE = 'FUTURE';

    private ?int $id;
    private int $year;
    private int $month;
    private string $negotiationType;
    private string $marketType;
    private float $result;
    private float $accumulatedLoss;
    private float $compesatedLoss;
    private float $basisToIr;
    private float $aliquot;
    private float $irrf;
    private float $accumulatedIrrf;
    private float $compesatedIrrf;
    private float $irrfToPay;
    private float $irToPay;

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
     * @return Consolidation
     */
    public function setMarketType(string $marketType): Consolidation
    {
        if (!in_array($marketType, self::getMarketTypes(), true)){
            throw new \InvalidArgumentException("Invalid market type");
        }

        $this->marketType = $marketType;

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
     * @return Consolidation
     */
    public function setResult(float $result): Consolidation
    {
        $this->result = $result;

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
    public function getBasisToIr(): float
    {
        return $this->basisToIr;
    }

    /**
     * @param float $basisToIr
     * @return Consolidation
     */
    public function setBasisToIr(float $basisToIr): Consolidation
    {
        $this->basisToIr = $basisToIr;

        return $this;
    }

    /**
     * @return float
     */
    public function getAliquot(): float
    {
        return $this->aliquot;
    }

    /**
     * @param float $aliquot
     * @return Consolidation
     */
    public function setAliquot(float $aliquot): Consolidation
    {
        $this->aliquot = $aliquot;

        return $this;
    }

    /**
     * @return float
     */
    public function getIrrf(): float
    {
        return $this->irrf;
    }

    /**
     * @param float $irrf
     * @return Consolidation
     */
    public function setIrrf(float $irrf): Consolidation
    {
        $this->irrf = $irrf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAccumulatedIrrf(): float
    {
        return $this->accumulatedIrrf;
    }

    /**
     * @param float $accumulatedIrrf
     * @return Consolidation
     */
    public function setAccumulatedIrrf(float $accumulatedIrrf): Consolidation
    {
        $this->accumulatedIrrf = $accumulatedIrrf;
        return $this;
    }

    /**
     * @return float
     */
    public function getCompesatedIrrf(): float
    {
        return $this->compesatedIrrf;
    }

    /**
     * @param float $compesatedIrrf
     * @return Consolidation
     */
    public function setCompesatedIrrf(float $compesatedIrrf): Consolidation
    {
        $this->compesatedIrrf = $compesatedIrrf;
        return $this;
    }

    /**
     * @return float
     */
    public function getIrrfToPay(): float
    {
        return $this->irrfToPay;
    }

    /**
     * @param float $irrfToPay
     * @return Consolidation
     */
    public function setIrrfToPay(float $irrfToPay): Consolidation
    {
        $this->irrfToPay = $irrfToPay;
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
            'negotiation_type' => $this->negotiationType,
            'market_type' => $this->marketType,
            'result' => $this->result,
            'accumulated_loss' => $this->accumulatedLoss,
            'compesated_loss' => $this->compesatedLoss,
            'basis_to_ir' => $this->basisToIr,
            'aliquot' => $this->aliquot,
            'irrf' => $this->irrf,
            'accumulated_irrf' => $this->accumulatedIrrf,
            'compesated_irrf' => $this->compesatedIrrf,
            'irrf_to_pay' => $this->irrfToPay,
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