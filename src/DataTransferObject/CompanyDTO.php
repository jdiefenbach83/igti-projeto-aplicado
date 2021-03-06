<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CompanyDTO implements DTOInterface
{
    private $cnpj;
    private $name;

    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param $cnpj
     * @return CompanyDTO
     */
    public function setCnpj($cnpj): CompanyDTO
    {
        $this->cnpj = $cnpj;
        
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return CompanyDTO
     */
    public function setName($name): CompanyDTO
    {
        $this->name = $name;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('cnpj', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('cnpj', new Length(null, null, 18, null, 'trim'));
        $metadata->addPropertyConstraint('name', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('name', new Length(null, null, 255, null, 'trim'));
    }
}