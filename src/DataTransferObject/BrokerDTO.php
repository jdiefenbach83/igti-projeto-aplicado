<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class BrokerDTO implements DTOInterface
{
    private $code;
    private $name;
    private $cnpj;
    private $site;

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     * @return BrokerDTO
     */
    public function setCode($code): BrokerDTO
    {
        $this->code = $code;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return BrokerDTO
     */
    public function setName($name): BrokerDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param $cnpj
     * @return BrokerDTO
     */
    public function setCnpj($cnpj): BrokerDTO
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    /**
     * @param $site
     * @return BrokerDTO
     */
    public function setSite($site): BrokerDTO
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
        $metadata->addPropertyConstraint('cnpj', new Length(null, null, 18, null, 'trim'));
        $metadata->addPropertyConstraint('site', new Length(null, null, 255, null, 'trim'));
        $metadata->addPropertyConstraint('site', new Url());
    }
}