<?php

namespace App\Helper;

use App\Entity\Broker;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BrokerFactory implements EntityFactoryInterface
{
    public function makeEntity(string $json)
    {
        $content = json_decode($json);

        return (new Broker())
            ->setCode($content->code ?? "")
            ->setName($content->name ?? "")
            ->setCnpj($content->cnpj ?? "")
            ->setSite($content->site ?? "");
    }
}