<?php

namespace App\Repository;

use App\Entity\BrokerageNote;

interface BrokerageNoteRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function add(BrokerageNote $brokerage_note): void;
    public function update(BrokerageNote $brokerage_note): void;
    public function remove(BrokerageNote $brokerage_note): void;
}