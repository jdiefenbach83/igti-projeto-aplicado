<?php

namespace App\Service\IrCalculator;

use App\Entity\Consolidation;

interface IrCalculatorMethod
{
    public function getAliquot(): float;
    public function calculateIrrf(): float;
    public function calculateIr(): float;
    public function isExempt(): bool;
}