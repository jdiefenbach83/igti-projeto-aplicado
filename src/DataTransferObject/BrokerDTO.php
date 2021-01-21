<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class BrokerDTO implements DTOInterface
{
    private ?string $code;
    private ?string $name;
    private ?string $cnpj;
    private ?string $site;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return BrokerDTO
     */
    public function setCode(?string $code): BrokerDTO
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return BrokerDTO
     */
    public function setName(?string $name): BrokerDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    /**
     * @param string|null $cnpj
     * @return BrokerDTO
     */
    public function setCnpj(?string $cnpj): BrokerDTO
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
     * @return BrokerDTO
     */
    public function setSite(?string $site): BrokerDTO
    {
        $this->site = $site;
        return $this;
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