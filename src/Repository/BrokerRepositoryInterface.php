<?php

namespace App\Repository;

use App\Entity\Broker;

interface BrokerRepositoryInterface extends WorkUnitInterface
{
    public function findAll();
    public function findById(int $id);
    public function save(Broker $broker): void;
    public function remove(Broker $broker): void;
}