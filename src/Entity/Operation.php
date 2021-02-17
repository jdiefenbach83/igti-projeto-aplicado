<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
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
            throw new \InvalidArgumentException("Invalid type");
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
     * @return float
     */
    private function calculateTotal(): void
    {
        $this->total = (float)bcmul($this->quantity, $this->price, 4);
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
            'line' => $this->line,
            'type' => $this->type,
            'asset_id' => $this->asset->getId(),
            'quantity' => $this->quantity,
            'price' => $this->price,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/Operation/' . $this->id
                ],
            ]
        ];
    }
}