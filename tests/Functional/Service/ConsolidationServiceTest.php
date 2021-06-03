<?php

namespace App\Tests\Functional\Service;

use App\Entity\Consolidation;
use App\Entity\PreConsolidation;
use App\Repository\PreConsolidationRepository;
use App\Service\AssetService;
use App\Service\ConsolidationService;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConsolidationServiceTest extends KernelTestCase
{
    private $preConsolidationRepository;
    private $consolidationService;
    private $assetService;

    private Generator $faker;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->preConsolidationRepository = self::$container->get(PreConsolidationRepository::class);
        $this->consolidationService = self::$container->get(ConsolidationService::class);
        $this->assetService = self::$container->get(AssetService::class);

        $this->faker = Factory::create();
    }

    public function getPreConsolidationsToConsolidate(): iterable
    {
        yield 'Spot Market - Normal operations - Tax changed' => [
            [
                'preConsolidations' => [
                    [
                        'assetCode' => 'VALE3',
                        'year' => 2020,
                        'month' => 9,
                        'negotiationType' => PreConsolidation::NEGOTIATION_TYPE_NORMAL,
                        'result' => 29994.0,
                        'marketType' => PreConsolidation::MARKET_TYPE_SPOT,
                        'assetType' => PreConsolidation::ASSET_TYPE_STOCK,
                    ]
                ],
                'consolidation' => [
                    'year' => 2020,
                    'month' => 9,
                    'assetType' => PreConsolidation::ASSET_TYPE_STOCK,
                    'negotiationType' => Consolidation::NEGOTIATION_TYPE_NORMAL,
                    'marketType' => Consolidation::MARKET_TYPE_SPOT,
                    'result' => 29994.0,
                    'accumulatedLoss' => .0,
                    'compesatedLoss'=> .0,
                    'basisToIr' => 29994.0,
                    'aliquot' => .15,
                    'irrf' => 149.97,
                    'accumulatedIrrf' => .0,
                    'compensatedIrrf' => .0,
                    'irrfToPay' => 149.97,
                    'ir' => 4499.1,
                    'irToPay' => 4349.13,
                ],
            ],
        ];
    }

    private function buildPreConsolidationToConsolidate(array $preConsolidations): void
    {
        foreach ($preConsolidations as $preConsolidation)
        {
            $asset = $this->assetService->getByCode($preConsolidation['assetCode']);

            $newPreConsolidation = new PreConsolidation();
            $newPreConsolidation
                ->setAsset($asset)
                ->setYear($preConsolidation['year'])
                ->setMonth($preConsolidation['month'])
                ->setAssetType($preConsolidation['assetType'])
                ->setNegotiationType($preConsolidation['negotiationType'])
                ->setMarketType($preConsolidation['marketType'])
                ->setResult($preConsolidation['result']);

            $this->preConsolidationRepository->save($newPreConsolidation);
        }
    }

    /**
     * @dataProvider getPreConsolidationsToConsolidate
     * @param array $preConsolidations
     */
    public function testConsolidationService_shouldPreConsolidatePositions(array $preConsolidations): void
    {
        $this->buildPreConsolidationToConsolidate($preConsolidations['preConsolidations']);
        $preConsolidationsTotal = count($preConsolidations['preConsolidations']);
        $insertedPreConsolidations = count($this->preConsolidationRepository->findAll());

        $this->consolidationService->process();

        /** @var Consolidation $consolidation */
        $consolidations = $this->consolidationService->getAll();

        self::assertEquals($preConsolidationsTotal, $insertedPreConsolidations);

        foreach($consolidations as $consolidation) {
            $shouldSkip =
                ($consolidation->getYear() !== $preConsolidations['consolidation']['year']) ||
                ($consolidation->getMonth() !== $preConsolidations['consolidation']['month']) ||
                ($consolidation->getMarketType() !== $preConsolidations['consolidation']['marketType']) ||
                ($consolidation->getNegotiationType() !== $preConsolidations['consolidation']['negotiationType']) ||
                ($consolidation->getAssetType() !== $preConsolidations['consolidation']['assetType'])
            ;

            if($shouldSkip) {
                continue;
            }

            self::assertEquals($preConsolidations['consolidation']['year'], $consolidation->getYear());
            self::assertEquals($preConsolidations['consolidation']['month'], $consolidation->getMonth());
            self::assertEquals($preConsolidations['consolidation']['assetType'], $consolidation->getAssetType());
            self::assertEquals($preConsolidations['consolidation']['negotiationType'], $consolidation->getNegotiationType());
            self::assertEquals($preConsolidations['consolidation']['marketType'], $consolidation->getMarketType());
            self::assertEquals($preConsolidations['consolidation']['result'], $consolidation->getResult());
            self::assertEquals($preConsolidations['consolidation']['accumulatedLoss'], $consolidation->getAccumulatedLoss());
            self::assertEquals($preConsolidations['consolidation']['compesatedLoss'], $consolidation->getCompesatedLoss());
            self::assertEquals($preConsolidations['consolidation']['basisToIr'], $consolidation->getBasisToIr());
            self::assertEquals($preConsolidations['consolidation']['aliquot'], $consolidation->getAliquot());
            self::assertEquals($preConsolidations['consolidation']['irrf'], $consolidation->getIrrf());
            self::assertEquals($preConsolidations['consolidation']['accumulatedIrrf'], $consolidation->getAccumulatedIrrf());
            self::assertEquals($preConsolidations['consolidation']['compensatedIrrf'], $consolidation->getCompesatedIrrf());
            self::assertEquals($preConsolidations['consolidation']['irrfToPay'], $consolidation->getIrrfToPay());
            self::assertEquals($preConsolidations['consolidation']['ir'], $consolidation->getIr());
            self::assertEquals($preConsolidations['consolidation']['irToPay'], $consolidation->getIrToPay());
        }
    }
}