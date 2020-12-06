<?php

namespace App\Entity;

use JsonSerializable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Broker implements EntityInterface, JsonSerializable
{
    private ?int $id;
    private string $code;
    private string $name;
    private string $cnpj;
    private ?string $site;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): self
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): self
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('code', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('code', new Length(null, null, 10, null, 'trim'));
        $metadata->addPropertyConstraint('name', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('name', new Length(null, null, 255, null, 'trim'));
        $metadata->addPropertyConstraint('cnpj', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('cnpj', new Length(null, null, 14, null, 'trim'));
        $metadata->addPropertyConstraint('site', new Length(null, null, 255, null, 'trim'));
        $metadata->addPropertyConstraint('site', new Url());
    }
}
