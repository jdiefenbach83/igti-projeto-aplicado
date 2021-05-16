<?php

namespace App\Service;

class CalculationService implements CalculationInterface
{
    /**
     * @var PositionService
     */
    private PositionService $positionService;

    /**
     * @var PreConsolidationService
     */
    private PreConsolidationService $preConsolidationService;

    /**
     * @var ConsolidationService
     */
    private ConsolidationService $consolidationService;

    public function __construct(
        PositionService $positionService,
        PreConsolidationService $preConsolidationService,
        ConsolidationService $consolidationService
    )
    {
        $this->positionService = $positionService;
        $this->preConsolidationService = $preConsolidationService;
        $this->consolidationService = $consolidationService;
    }

    public function process(): void
    {
        $this->positionService->process();
        $this->preConsolidationService->process();
        $this->consolidationService->process();
    }
}