<?php

namespace App\Helper;

use App\DataTransferObject\CompanyDTO;
use App\Entity\Company;

class CompanyFactory
{
    /**
     * @param CompanyDTO $dto
     * @return Company
     */
    public function makeEntityFromDTO(CompanyDTO $dto): Company
    {
        return $this->makeEntity(
            $dto->getCnpj(),
            $dto->getName(),
        );
    }

    /**
     * @param string $cnpj
     * @param string $name
     * @return Company
     */
    public function makeEntity(string $cnpj, string $name): Company
    {
        return (new Company())
            ->setCnpj($cnpj)
            ->setName($name);
    }
}
