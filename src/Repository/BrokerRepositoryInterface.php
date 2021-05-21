<?php

namespace App\Repository;

use App\Entity\Broker;

interface BrokerRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function save(Broker $broker): void;
    public function remove(Broker $broker): void;
}