<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Asset implements EntityInterface, JsonSerializable
{
    use Timestampable;

    const TYPE_STOCK = 'STOCK';
    const TYPE_FUTURE_CONTRACT = 'FUTURE_CONTRACT';

    private ?int $id;
    private string $code;
    private string $type;
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
        if (!in_array($type, array(self::TYPE_STOCK, self::TYPE_FUTURE_CONTRACT))) {
            throw new \InvalidArgumentException("Invalid type");
        }

        $this->type = $type;

        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_STOCK,
            self::TYPE_FUTURE_CONTRACT,
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
