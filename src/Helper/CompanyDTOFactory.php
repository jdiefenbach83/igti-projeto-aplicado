<?php

namespace App\Helper;

use App\DataTransferObject\CompanyDTO;

class CompanyDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json)
    {
        $content = json_decode($json);

        return (new CompanyDTO())
            ->setCnpj($content->cnpj ?? null)
            ->setName($content->name ?? null);
    }
}