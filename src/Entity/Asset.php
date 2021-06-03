<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Asset implements EntityInterface, JsonSerializable
{
    use Timestampable;

    public const TYPE_STOCK = 'STOCK';
    public const TYPE_INDEX = 'INDEX';
    public const TYPE_DOLAR = 'DOLAR';

    public const MARKET_TYPE_SPOT = 'SPOT';
    public const MARKET_TYPE_FUTURE = 'FUTURE';

    private ?int $id;
    private string $code;
    private string $type;
    private string $marketType;
    private ?Company $company;
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

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
     * @return Asset
     */
    public function setType(string $type): self
    {
        if (!in_array($type, self::getTypes(), true)) {
            throw new \InvalidArgumentException("Invalid type");
        }

        $this->type = $type;

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
     * @return Asset
     */
    public function setMarketType(string $marketType): self
    {
        if (!in_array($marketType, self::getMarketTypes(), true)) {
            throw new \InvalidArgumentException('Invalid market type');
        }

        $this->marketType = $marketType;

        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_STOCK,
            self::TYPE_INDEX,
            self::TYPE_DOLAR,
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
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     * @return Asset
     */
    public function setCompany(?Company $company): Asset
    {
        $this->company = $company;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'market_type' => $this->marketType,
            'company_id' => is_null($this->company) ? null : $this->company->getId(),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/assets/' . $this->id
                ],
            ]
        ];
    }
}
