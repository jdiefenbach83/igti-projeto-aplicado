<?php

namespace App\Helper;

use App\DataTransferObject\AssetDTO;
use App\Entity\Asset;

class AssetFactory implements EntityFactoryInterface
{
    /**
     * @param AssetDTO $dto
     * @return Asset
     */
    public function makeEntity($dto)
    {
        return (new Asset())
            ->setCode($dto->getCode())
            ->setType($dto->getType())
            ->setDescription($dto->getDescription());
    }
}
