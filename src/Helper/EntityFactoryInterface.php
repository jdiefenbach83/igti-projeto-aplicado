<?php

namespace App\Helper;

use App\DataTransferObject\DTOInterface;

interface EntityFactoryInterface
{
    /**
     * @param DTOInterface $dto
     * @return mixed
     */
    public function makeEntity($dto);
}