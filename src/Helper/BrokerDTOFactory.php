<?php

namespace App\Helper;

use App\DataTransferObject\BrokerDTO;

class BrokerDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json)
    {
        $content = json_decode($json);

        return (new BrokerDTO())
            ->setCode($content->code ?? null)
            ->setName($content->name ?? null)
            ->setCnpj($content->cnpj ?? null)
            ->setSite($content->site ?? null);
    }
}