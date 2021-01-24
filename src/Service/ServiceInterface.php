<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\EntityInterface;

interface ServiceInterface
{
    public function getAll(): array;
    public function getById(int $id);
    public function add(DTOInterface $dto): ?EntityInterface;
    public function update(int $id, DTOInterface $dto): ?EntityInterface;
    public function remove(int $id): void;

    public function getValidationErrors();
}