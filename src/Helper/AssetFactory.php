<?php

namespace App\Helper;

use App\DataTransferObject\AssetDTO;
use App\Entity\Asset;

class AssetFactory
{
    /**
     * @param AssetDTO $dto
     * @return Asset
     */
    public function makeEntityFromDTO(AssetDTO $dto): Asset
    {
        return $this->makeEntity(
            $dto->getCode(),
            $dto->getType(),
            $dto->getDescription()
        );
    }

    /**
     * @param string $code
     * @param string $type
     * @param string $description
     * @return Asset
     */
    public function makeEntity(string $code, string $type, string $description): Asset
    {
        return (new Asset())
            ->setCode($code)
            ->setType($type)
            ->setDescription($description);
    }
}
