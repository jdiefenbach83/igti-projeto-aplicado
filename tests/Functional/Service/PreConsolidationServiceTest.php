<?php

namespace App\Tests\Functional\Service;

use App\Entity\Position;
use App\Entity\PreConsolidation;
use App\Repository\PositionRepository;
use App\Repository\PreConsolidationRepository;
use App\Service\AssetService;
use App\Service\PreConsolidationService;
use App\Service\PositionService;
use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PreConsolidationServiceTest extends KernelTestCase
{
    private $positionRepository;
    private $positionService;
    private $preConsolidationService;
    private $preConsolidationRepository;
    private $assetService;

    private Generator $faker;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->positionRepository = self::$container->get(PositionRepository::class);
        $this->positionService = self::$container->get(PositionService::class);
        $this->preConsolidationService = self::$container->get(PreConsolidationService::class);
        $this->preConsolidationRepository = self::$container->get(PreConsolidationRepository::class);
        $this->assetService = self::$container->get(AssetService::class);

        $this->faker = Factory::create();
    }

    public function getPositionsToPreConsolidate(): iterable
    {
        yield 'Positive results' => [
            [
                'Positions' => [
                    [
                        'assetCode' => 'PETR3',
                        'type' => Position::TYPE_BUY,
                        'date' => '2021-04-12',
                        'quantity' => 100,
                        'unitPrice' => 9.0,
                        'totalOperation' => 900.0,
                        'totalCosts' => 21.5,
                        'positionPrice' => 9.215,
                        'accumulatedQuantity' => 100,
                        'accumulatedTotal' => 900.0,
                        'accumulatedCosts' => 21.5,
                        'averagePrice' => 9.215,
                        'averagePriceToIr' => 9.215,
                        'result' => 0.0,
                        'accumulatedResult' => 0.0,
                        'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL
                    ],
                    [
                        'assetCode' => 'PETR3',
                        'type' => Position::TYPE_BUY,
                        'date' => '2021-04-13',
                        'quantity' => 100,
                        'unitPrice' => 12.0,
                        'totalOperation' => 1200.0,
                        'totalCosts' => 21.7,
                        'positionPrice' => 12.217,
                        'accumulatedQuantity' => 200,
                        'accumulatedTotal' => 2100.0,
                        'accumulatedCosts' => 43.2,
                        'averagePrice' => 10.716,
                        'averagePriceToIr' => 10.716,
                        'result' => 0.0,
                        'accumulatedResult' => 0.0,
                        'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL
                    ],
                    [
                        'assetCode' => 'PETR3',
                        'type' => Position::TYPE_SELL,
                        'date' => '2021-04-14',
                        'quantity' => 50,
                        'unitPrice' => 11.5,
                        'totalOperation' => 575.0,
                        'totalCosts' => 20.86,
                        'positionPrice' => 11.0828,
                        'accumulatedQuantity' => 150,
                        'accumulatedTotal' => 1525.0,
                        'accumulatedCosts' => 22.34,
                        'averagePrice' => 10.716,
                        'averagePriceToIr' => 10.716,
                        'result' => 18.34,
                        'accumulatedResult' => 18.34,
                        'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL
                    ],
                    [
                        'assetCode' => 'PETR3',
                        'type' => Position::TYPE_BUY,
                        'date' => '2021-04-15',
                        'quantity' => 200,
                        'unitPrice' => 15.0,
                        'totalOperation' => 3000.0,
                        'totalCosts' => 23.6000,
                        'positionPrice' => 15.118,
                        'accumulatedQuantity' => 350,
                        'accumulatedTotal' => 4525.0,
                        'accumulatedCosts' => 45.94,
                        'averagePrice' => 13.0598,
                        'averagePriceToIr' => 13.2314,
                        'result' => 0.0,
                        'accumulatedResult' => 18.34,
                        'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL
                    ],
                ],
                'PreConsolidation' => [
                    'assetCode' => 'PETR3',
                    'year' => 2021,
                    'month' => 4,
                    'negotiationType' => PreConsolidation::NEGOTIATION_TYPE_NORMAL,
                    'result' => 18.34,
                    'marketType' => PreConsolidation::MARKET_TYPE_SPOT,
                    'assetType' => PreConsolidation::ASSET_TYPE_STOCK,
                ],
            ],
        ];
        yield 'Negative results' => [
            [
                'Positions' => [
                    [
                        'assetCode' => 'PETR4',
                        'type' => Position::TYPE_BUY,
                        'date' => '2021-04-13',
                        'quantity' => 10,
                        'unitPrice' => 20.0,
                        'totalOperation' => 200.0,
                        'totalCosts' => 5.42,
                        'positionPrice' => 20.542,
                        'accumulatedQuantity' => 10,
                        'accumulatedTotal' => 200.0,
                        'accumulatedCosts' => 5.42,
                        'averagePrice' => 20.542,
                        'averagePriceToIr' => 20.542,
                        'result' => 0.0,
                        'accumulatedResult' => 0.0,
                        'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE
                    ],
                    [
                        'assetCode' => 'PETR4',
                        'type' => Position::TYPE_SELL,
                        'date' => '2021-04-13',
                        'quantity' => 10,
                        'unitPrice' => 20,
                        'totalOperation' => 100.0,
                        'totalCosts' => 5.42,
                        'positionPrice' => 9.458,
                        'accumulatedQuantity' => 0,
                        'accumulatedTotal' => .0,
                        'accumulatedCosts' => 0.,
                        'averagePrice' => 20.542,
                        'averagePriceToIr' => 20.542,
                        'result' => -110.84,
                        'accumulatedResult' => -110.84,
                        'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE
                    ],
                ],
                'PreConsolidation' => [
                    'assetCode' => 'PETR4',
                    'year' => 2021,
                    'month' => 4,
                    'negotiationType' => PreConsolidation::NEGOTIATION_TYPE_DAYTRADE,
                    'result' => -110.84,
                    'marketType' => PreConsolidation::MARKET_TYPE_SPOT,
                    'assetType' => PreConsolidation::ASSET_TYPE_STOCK,
                ],
            ],
        ];
    }

    private function buildPositionsToPreConsolidate(array $positions): void
    {
        $index = 0;

        foreach ($positions as $position) {

            $asset = $this->assetService->getByCode($position['assetCode']);

            $newPosition = new Position();
            $newPosition
                ->setAsset($asset)
                ->setType($position['type'])
                ->setDate(new DateTimeImmutable($position['date']))
                ->setQuantity($position['quantity'])
                ->setUnitPrice($position['unitPrice'])
                ->setTotalOperation($position['totalOperation'])
                ->setTotalCosts($position['totalCosts'])
                ->setPositionPrice($position['positionPrice'])
                ->setAccumulatedQuantity($position['accumulatedQuantity'])
                ->setAccumulatedTotal($position['accumulatedTotal'])
                ->setAccumulatedCosts($position['accumulatedCosts'])
                ->setAveragePrice($position['averagePrice'])
                ->setAveragePriceToIr($position['averagePriceToIr'])
                ->setResult($position['result'])
                ->setAccumulatedResult($position['accumulatedResult'])
                ->setNegotiationType($position['negotiationType'])
                ->setSequence(++$index);

            $this->positionRepository->save($newPosition);
        }
    }

    /**
     * @dataProvider getPositionsToPreConsolidate
     * @param array $positions
     */
    public function testPreConsolidationService_shouldPreConsolidatePositions(array $positions): void
    {
        $this->buildPositionsToPreConsolidate($positions['Positions']);
        $positionsTotal = count($positions['Positions']);
        $insertedPositions = count($this->positionService->getAll());

        $this->preConsolidationService->process();

        /** @var PreConsolidation $preConsolidation */
        $preConsolidation = $this->preConsolidationRepository->findAll()[0];

        self::assertEquals($positionsTotal, $insertedPositions);
        self::assertEquals($positions['PreConsolidation']['assetCode'], $preConsolidation->getAsset()->getCode());
        self::assertEquals($positions['PreConsolidation']['year'], $preConsolidation->getYear());
        self::assertEquals($positions['PreConsolidation']['month'], $preConsolidation->getMonth());
        self::assertEquals($positions['PreConsolidation']['assetType'], $preConsolidation->getAssetType());
        self::assertEquals($positions['PreConsolidation']['negotiationType'], $preConsolidation->getNegotiationType());
        self::assertEquals($positions['PreConsolidation']['result'], $preConsolidation->getResult());
        self::assertEquals($positions['PreConsolidation']['marketType'], $preConsolidation->getMarketType());
    }
}
