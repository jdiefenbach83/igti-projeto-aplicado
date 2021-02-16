<?php

namespace App\DataTransferObject;

use App\Entity\Operation;
use App\Validator\AssetExists;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class OperationDTO implements DTOInterface
{
    private $type;
    private $asset_id;
    private $quantity;
    private $price;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return OperationDTO
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssetId()
    {
        return $this->asset_id;
    }

    /**
     * @param mixed $asset_id
     * @return OperationDTO
     */
    public function setAssetId($asset_id): self
    {
        $this->asset_id = $asset_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return OperationDTO
     */
    public function setQuantity($quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return OperationDTO
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('type', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('type', new Choice(['callback' => [Operation::class, 'getTypes']]));
        $metadata->addPropertyConstraint('asset_id', new NotNull());
        $metadata->addPropertyConstraint('asset_id', new NotBlank());
        $metadata->addPropertyConstraint('asset_id', new AssetExists());
        $metadata->addPropertyConstraint('quantity', new NotBlank());
        $metadata->addPropertyConstraint('quantity', new PositiveOrZero());
        $metadata->addPropertyConstraint('price', new NotBlank());
        $metadata->addPropertyConstraint('price', new PositiveOrZero());
    }
}