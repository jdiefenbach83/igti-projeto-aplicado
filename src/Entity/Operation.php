<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use InvalidArgumentException;
use JsonSerializable;

class Operation implements EntityInterface, JsonSerializable
{
    use Timestampable;

    public const TYPE_BUY = 'BUY';
    public const TYPE_SELL = 'SELL';

    private ?int $id;
    private int $line;
    private string $type;
    private Asset $asset;
    private int $quantity;
    private float $price;
    private float $total;
    private BrokerageNote $brokerageNote;
    private float $operational_fee;
    private float $registration_fee;
    private float $emolument_fee;
    private float $total_fees;
    private float $brokerage;
    private float $iss_pis_cofins;
    private float $total_costs;
    private ?Position $position;

    /**
     * Operation constructor.
     * @param int $line
     * @param string $type
     * @param Asset $asset
     * @param int $quantity
     * @param float $price
     * @param BrokerageNote $brokerageNote
     */
    public function __construct(int $line, string $type, Asset $asset, int $quantity, float $price, BrokerageNote $brokerageNote)
    {
        $this->line = $line;
        $this->setType($type);
        $this->asset = $asset;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->brokerageNote = $brokerageNote;
        $this->operational_fee = .0;
        $this->registration_fee = .0;
        $this->emolument_fee = .0;
        $this->brokerage = .0;
        $this->iss_pis_cofins = .0;

        $this->calculateTotal();
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
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return Operation
     */
    public function setLine(int $line): Operation
    {
        $this->line = $line;

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
     * @return Operation
     */
    public function setType(string $type): Operation
    {
        if (!in_array($type, self::getTypes(), true)) {
            throw new InvalidArgumentException("Invalid type");
        }

        $this->type = $type;

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
     * @return Operation
     */
    public function setAsset(Asset $asset): self
    {
        $this->asset = $asset;

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
     * @return Operation
     */
    public function setQuantity(int $quantity): Operation
    {
        $this->quantity = $quantity;
        $this->calculateTotal();

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Operation
     */
    public function setPrice(float $price): Operation
    {
        $this->price = $price;
        $this->calculateTotal();

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @return void
     */
    private function calculateTotal(): void
    {
        $this->total = (float)bcmul($this->quantity, $this->price, 4);
    }

    public function getTotalForCalculations(): float
    {
        $total = $this->total;
        $operation = $this->type === self::TYPE_BUY ? -1 : 1;

        return bcmul($total, $operation, 4);
    }

    /**
     * @return BrokerageNote
     */
    public function getBrokerageNote(): BrokerageNote
    {
        return $this->brokerageNote;
    }

    /**
     * @param BrokerageNote $brokerageNote
     * @return Operation
     */
    public function setBrokerageNote(BrokerageNote $brokerageNote): Operation
    {
        $this->brokerageNote = $brokerageNote;

        return $this;
    }

    public function getOperationalFee(): float
    {
        return $this->operational_fee;
    }

    public function setOperationalFee(float $operationalFee): Operation
    {
        $this->operational_fee = $operationalFee;
        $this->calculateFeesAndCosts();

        return $this;
    }

    /**
     * @return float
     */
    public function getRegistrationFee(): float
    {
        return $this->registration_fee;
    }

    /**
     * @param float $registration_fee
     * @return Operation
     */
    public function setRegistrationFee(float $registration_fee): Operation
    {
        $this->registration_fee = $registration_fee;
        $this->calculateFeesAndCosts();

        return $this;
    }

    /**
     * @return float
     */
    public function getEmolumentFee(): float
    {
        return $this->emolument_fee;
    }

    /**
     * @param float $emolument_fee
     * @return Operation
     */
    public function setEmolumentFee(float $emolument_fee): Operation
    {
        $this->emolument_fee = $emolument_fee;
        $this->calculateFeesAndCosts();

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalFees(): float
    {
        return $this->total_fees;
    }

    /**
     * @return float
     */
    public function getIssPisCofins(): float
    {
        return $this->iss_pis_cofins;
    }

    /**
     * @param float $iss_pis_cofins
     * @return Operation
     */
    public function setIssPisCofins(float $iss_pis_cofins): Operation
    {
        $this->iss_pis_cofins = $iss_pis_cofins;
        $this->calculateFeesAndCosts();

        return $this;
    }

    /**
     * @return float
     */
    public function getBrokerage(): float
    {
        return $this->brokerage;
    }

    /**
     * @param float $brokerage
     * @return Operation
     */
    public function setBrokerage(float $brokerage): Operation
    {
        $this->brokerage = $brokerage;
        $this->calculateFeesAndCosts();

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalCosts(): float
    {
        return $this->total_costs;
    }

    /**
     * @return Position|null
     */
    public function getPosition(): ?Position
    {
        return $this->position;
    }

    /**
     * @param Position|null $position
     * @return Operation
     */
    public function setPosition(?Position $position): Operation
    {
        $this->position = $position;

        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_BUY,
            self::TYPE_SELL,
        ];
    }

    private function calculateFeesAndCosts(): void
    {
        $this->calculateFees();
        $this->calculateTotalCosts();
    }

    private function calculateFees(): void
    {
        $this->total_fees = bcadd($this->operational_fee, $this->registration_fee, 4);
        $this->total_fees = bcadd($this->total_fees, $this->emolument_fee, 4);
    }

    private function calculateTotalCosts(): void
    {
        $this->total_costs = bcadd($this->total_fees, $this->brokerage, 4);
        $this->total_costs = bcadd($this->total_costs, $this->iss_pis_cofins, 4);
    }

    public function getGrossTotal(): float
    {
        $totalCosts = bcadd($this->total_fees, $this->total_costs, 2);

        return bcadd($this->getTotal(), $totalCosts, 2);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'brokerage_note_id' => $this->brokerageNote->getId(),
            'line' => $this->line,
            'type' => $this->type,
            'asset_id' => $this->asset->getId(),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->getTotal(),
            'operational_fee' => $this->operational_fee,
            'registration_fee' => $this->registration_fee,
            'emolument_fee' => $this->emolument_fee,
            'brokerage' => $this->brokerage,
            'iss_pis_cofins' => $this->iss_pis_cofins,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/Operation/' . $this->id
                ],
            ]
        ];
    }
}