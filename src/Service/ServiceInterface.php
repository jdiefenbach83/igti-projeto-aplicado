<?php

namespace App\Service;

use App\Entity\EntityInterface;

interface ServiceInterface
{
    public function getAll(): array;
    public function getById(int $id);
    public function add(EntityInterface $entity): bool;
    public function update(int $id, EntityInterface $broker): ?EntityInterface;
    public function remove(int $id): void;

    public function getValidationErrors();
}