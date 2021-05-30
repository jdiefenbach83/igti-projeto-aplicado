<?php

namespace App\Service\IrCalculator;

use App\Entity\Consolidation;

class DaytradeMethod implements IrCalculatorMethod
{
    private const IRRF_ALIQUOT = .005;
    private const IR_ALIQUOT = .2;

    private Consolidation $consolidation;

    public function __construct(Consolidation $consolidation)
    {
        $this->consolidation = $consolidation;
    }

    public function getAliquot(): float
    {
        if ($this->shouldCalculate() === false) {
            return .0;
        }

        return self::IR_ALIQUOT;
    }

    public function calculateIrrf(): float
    {
        if ($this->shouldCalculate() === false) {
            return .0;
        }

        return bcmul($this->consolidation->getBasisToIr(), self::IRRF_ALIQUOT, 6);
    }

    public function calculateIr(): float
    {
        if ($this->shouldCalculate() === false) {
            return .0;
        }

        return bcmul($this->consolidation->getBasisToIr(), self::IR_ALIQUOT, 6);
    }

    private function shouldCalculate(): bool
    {
        return $this->consolidation->getBasisToIr() > .0;
    }
}