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

    /**
     * @var GoodService
     */
    private GoodService $goodService;

    public function __construct(
        PositionService $positionService,
        PreConsolidationService $preConsolidationService,
        ConsolidationService $consolidationService,
        GoodService $goodService
    )
    {
        $this->positionService = $positionService;
        $this->preConsolidationService = $preConsolidationService;
        $this->consolidationService = $consolidationService;
        $this->goodService = $goodService;
    }

    public function process(): void
    {
        $this->positionService->process();
        $this->preConsolidationService->process();
        $this->consolidationService->process();
        $this->goodService->process();
    }
}