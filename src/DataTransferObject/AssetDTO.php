<?php

namespace App\DataTransferObject;

use App\Entity\Asset;
use App\Validator\CompanyExists;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class AssetDTO implements DTOInterface
{
    private $code;
    private $type;
    private $company_id;

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     * @return AssetDTO
     */
    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return AssetDTO
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * @param mixed $company_id
     * @return AssetDTO
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('code', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('code', new Length(null, null, 10, null, 'trim'));
        $metadata->addPropertyConstraint('type', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('type', new Choice(['callback' => [Asset::class, 'getTypes']]));
        $metadata->addPropertyConstraint('company_id', new Length(null, null, 255, null, 'trim'));
        $metadata->addPropertyConstraint('company_id', new CompanyExists());
    }
}