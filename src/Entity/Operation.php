<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use InvalidArgumentException;
use JsonSerializable;

class Operation implements EntityInterface, JsonSerializable
{
    use Timestampable;

    const TYPE_BUY = 'BUY';
    const TYPE_SELL = 'SELL';

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
        $this->type = $type;
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
        if (!in_array($type, array(self::TYPE_BUY, self::TYPE_SELL))) {
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

        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_BUY,
            self::TYPE_SELL,
        ];
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