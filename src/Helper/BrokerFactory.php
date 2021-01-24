<?php

namespace App\Helper;

use App\DataTransferObject\BrokerDTO;
use App\Entity\Broker;

class BrokerFactory implements EntityFactoryInterface
{
    /**
     * @param BrokerDTO $dto
     * @return Broker
     */
    public function makeEntity($dto)
    {
        return (new Broker())
            ->setCode($dto->getCode())
            ->setName($dto->getName())
            ->setCnpj($dto->getCnpj())
            ->setSite($dto->getSite());
    }
}
