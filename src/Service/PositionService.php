<?php

namespace App\Service;

use App\Entity\BrokerageNote;
use App\Entity\Operation;
use App\Entity\Position;
use App\Repository\BrokerageNoteRepositoryInterface;
use App\Repository\PositionRepositoryInterface;

class PositionService
{
    /**
     * @var PositionRepositoryInterface
     */
    private PositionRepositoryInterface $positionRepository;
    /**
     * @var BrokerageNoteRepositoryInterface
     */
    private BrokerageNoteRepositoryInterface $brokerageNoteRepository;

    /**
     * PositionService constructor.
     * @param PositionRepositoryInterface $positionRepository
     * @param BrokerageNoteRepositoryInterface $brokerageNoteRepository
     */
    public function __construct(
        PositionRepositoryInterface $positionRepository,
        BrokerageNoteRepositoryInterface $brokerageNoteRepository
    )
    {
        $this->positionRepository = $positionRepository;
        $this->brokerageNoteRepository = $brokerageNoteRepository;
    }

    public function getAll(): array
    {
        return $this->positionRepository->findAll();
    }

    public function processPosition(): void
    {
        $this->removePositionsByOperation();
        $this->createPositions();
        $this->calculatePositions();
    }

    private function removePositionsByOperation(): void
    {
        $positions = $this->positionRepository->findAll();

        foreach ($positions as $position){
            $this->positionRepository->remove($position);
        }
    }

    private function createPositions(): void {
        $brokerageNotes = $this->brokerageNoteRepository->findAll();

        foreach ($brokerageNotes as $brokerageNote){
            $this->addPosition($brokerageNote);
        }
    }

    private function addPosition(BrokerageNote $brokerageNote): void
    {
        if (!$brokerageNote->hasOperationsCompleted())
        {
            return;
        }

        /** @var Operation $operation */
        foreach ($brokerageNote->getOperations() as $operation) {
            $unitCost = bcdiv($operation->getGrossTotal(), $operation->getQuantity(), 2);

            $position = new Position();
            $position
                ->setAsset($operation->getAsset())
                ->setOperation($operation)
                ->setSequence(0)
                ->setType($operation->getType())
                ->setDate($brokerageNote->getDate())
                ->setQuantity($operation->getQuantity())
                ->setUnitCost($unitCost)
                ->setTotalOperation($operation->getGrossTotal())
                ->setAccumulatedQuantity(0)
                ->setAccumulatedTotal(.0)
                ->setAveragePrice(.0);

            $this->positionRepository->add($position);
        }
    }

    private function calculatePositions(): void
    {
        $assetIds = $this->positionRepository->findAllAssets();

        foreach ($assetIds as $assetId)
        {
            $positions = $this->positionRepository->findByAsset($assetId['id']);
            $sequence = 1;

            $lastPositionAccumulatedQuantity = 0;
            $lastPositionAccumulatedTotal = 0;
            /**
             * @var int $current
             * @var Position $currentValue */
            foreach ($positions as $current => $currentValue) {
                $position = $currentValue;

                $position->setSequence($sequence++);

                if ($sequence === 1) {
                    $accumulatedQuantity = $position->getQuantity();
                    $accumulatedTotal = $position->getTotalOperation();
                } else {
                    $signal = ($position->getType() === Position::TYPE_BUY) ? 1 : -1;

                    $accumulatedQuantity = bcadd($position->getQuantity(), ($lastPositionAccumulatedQuantity * $signal), 2);
                    $accumulatedTotal = bcadd($position->getTotalOperation(),  ($lastPositionAccumulatedTotal * $signal), 2);
                }

                $divisor = $accumulatedQuantity === 0 ? 1 : $accumulatedQuantity;
                $averagePrice = bcdiv($accumulatedTotal, $divisor, 2);

                $position->setAccumulatedQuantity($accumulatedQuantity);
                $position->setAccumulatedTotal($accumulatedTotal);
                $position->setAveragePrice($averagePrice);

                $this->positionRepository->update($position);

                $lastPositionAccumulatedQuantity = $accumulatedQuantity;
                $lastPositionAccumulatedTotal = $accumulatedTotal;
            }
        }
    }
}