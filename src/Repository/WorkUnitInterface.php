<?php

namespace App\Repository;

interface WorkUnitInterface
{
    public function startWorkUnit(): void;
    public function endWorkUnit(): void;
    public function processWorkUnit() : void;
}