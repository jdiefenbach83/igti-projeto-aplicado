<?php

namespace App\Repository;

use App\Entity\Broker;

interface BrokerRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function add(Broker $broker): void;
    public function update(Broker $broker): void;
    public function remove(Broker $broker): void;
}