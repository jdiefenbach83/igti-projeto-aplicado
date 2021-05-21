<?php

namespace App\Repository;

use App\Entity\Company;

interface CompanyRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function save(Company $company): void;
    public function remove(Company $company): void;
    public function findByCnpj(string $cnpj);
}