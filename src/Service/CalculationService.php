<?php

namespace App\Service;

class CalculationService implements CalculationInterface
{
    /**
     * @var PositionService
     */
    private PositionService $positionService;
    private ConsolidationService $consolidationService;

    public function __construct(PositionService $positionService, ConsolidationService $consolidationService)
    {
        $this->positionService = $positionService;
        $this->consolidationService = $consolidationService;
    }

    public function process(): void
    {
        $this->positionService->process();
        $this->consolidationService->process();
    }
}