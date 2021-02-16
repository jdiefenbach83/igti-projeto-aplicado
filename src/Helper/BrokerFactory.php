<?php

namespace App\Helper;

use App\DataTransferObject\BrokerDTO;
use App\Entity\Broker;

class BrokerFactory
{
    /**
     * @param BrokerDTO $dto
     * @return Broker
     */
    public function makeEntityFromDTO(BrokerDTO $dto): Broker
    {
        return $this->makeEntity(
            $dto->getCode(),
            $dto->getName(),
            $dto->getCnpj(),
            $dto->getSite()
        );
    }

    /**
     * @param string $code
     * @param string $name
     * @param string $cnpj
     * @param string|null $site
     * @return Broker
     */
    public function makeEntity(string $code, string $name, string $cnpj, ?string $site): Broker
    {
        return (new Broker())
            ->setCode($code)
            ->setName($name)
            ->setCnpj($cnpj)
            ->setSite($site);
    }
}
