<?php


namespace App\Service;


class CalculationService implements CalculationInterface
{
    /**
     * @var PositionService
     */
    private PositionService $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function process(): void
    {
        $this->positionService->process();
    }
}