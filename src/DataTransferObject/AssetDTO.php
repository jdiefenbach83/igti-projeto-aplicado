<?php

namespace App\DataTransferObject;

use App\Entity\Asset;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class AssetDTO implements DTOInterface
{
    private $code;
    private $type;
    private $description;

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

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return AssetDTO
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('code', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('code', new Length(null, null, 10, null, 'trim'));
        $metadata->addPropertyConstraint('type', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('type', new Choice(['callback' => [Asset::class, 'getTypes']]));
        $metadata->addPropertyConstraint('description', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('description', new Length(null, null, 255, null, 'trim'));
    }
}