<?php

namespace App\Repository;

use App\Entity\Position;

interface PositionRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function add(Position $position): void;
    public function update(Position $position): void;
    public function remove(Position $position): void;
}