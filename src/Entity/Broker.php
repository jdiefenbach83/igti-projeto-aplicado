<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Broker implements EntityInterface, JsonSerializable
{
    use Timestampable;

    private ?int $id;
    private string $code;
    private string $name;
    private string $cnpj;
    private ?string $site;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Broker
     */
    public function setName(string $name): Broker
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): Broker
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSite(): ?string
    {
        return $this->site;
    }

    /**
     * @param string|null $site
     * @return Broker
     */
    public function setSite(?string $site): Broker
    {
        $this->site = $site;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'cnpj' => $this->cnpj,
            'site' => $this->site,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/brokers/' . $this->id
                ],
            ]
        ];
    }
}
