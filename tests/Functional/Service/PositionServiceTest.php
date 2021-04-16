<?php

namespace App\Tests\Functional\Service;

use App\DataTransferObject\BrokerageNoteDTO;
use App\DataTransferObject\OperationDTO;
use App\Entity\Asset;
use App\Entity\Broker;
use App\Entity\BrokerageNote;
use App\Entity\Operation;
use App\Entity\Position;
use App\Service\AssetService;
use App\Service\BrokerageNoteService;
use App\Service\BrokerService;
use App\Service\PositionService;
use DateTime;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PositionServiceTest extends KernelTestCase
{
    private Generator $faker;

    private $brokerService;
    private $brokerageNoteService;
    private $assetService;
    private $positionService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->brokerService = self::$container->get(BrokerService::class);
        $this->brokerageNoteService = self::$container->get(BrokerageNoteService::class);
        $this->assetService = self::$container->get(AssetService::class);
        $this->positionService = self::$container->get(PositionService::class);

        $this->faker = Factory::create();
    }

    public function getBrokerageNotesToCalculatePositions(): iterable
    {
        yield 'Buying only' => [
            'brokerageNotes' => [
                [
                    'totalMoviments' => -900,
                    'operationalFee' => 21.5,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_BUY,
                            'quantity' => 100,
                            'price' => 9,
                        ]
                    ]
                ],
                [
                    'totalMoviments' => -1200,
                    'operationalFee' => 21.7,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_BUY,
                            'quantity' => 100,
                            'price' => 12,
                        ]
                    ]
                ],
                [
                    'totalMoviments' => -3000,
                    'operationalFee' => 23.6,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_BUY,
                            'quantity' => 200,
                            'price' => 15,
                        ]
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Operation::TYPE_BUY,
                    'quantity' => 100,
                    'unitPrice' => 9.0,
                    'totalOperation' => 900.0,
                    'totalCosts' => 21.5,
                    'positionPrice' => 9.215,
                    'accumulatedQuantity' => 100,
                    'accumulatedTotal' => 900,
                    'accumulatedCosts' => 21.5,
                    'averagePrice' => 9.215,
                    'averagePriceToIr' => 9.215,
                ],
                [
                    'type' => Operation::TYPE_BUY,
                    'quantity' => 100,
                    'unitPrice' => 12.0,
                    'totalOperation' => 1200.0,
                    'totalCosts' => 21.7,
                    'positionPrice' => 12.217,
                    'accumulatedQuantity' => 200,
                    'accumulatedTotal' => 2100,
                    'accumulatedCosts' => 43.2,
                    'averagePrice' => 10.716,
                    'averagePriceToIr' => 10.716,
                ],
                [
                    'type' => Operation::TYPE_BUY,
                    'quantity' => 200,
                    'unitPrice' => 15.0,
                    'totalOperation' => 3000.0,
                    'totalCosts' => 23.6,
                    'positionPrice' => 15.118,
                    'accumulatedQuantity' => 400,
                    'accumulatedTotal' => 5100,
                    'accumulatedCosts' => 66.8,
                    'averagePrice' => 12.917,
                    'averagePriceToIr' => 12.917,
                ],
            ],
        ];

        yield 'Buying and sell' => [
            'brokerageNotes' => [
                [
                    'totalMoviments' => -900,
                    'operationalFee' => 21.5,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_BUY,
                            'quantity' => 100,
                            'price' => 9,
                        ]
                    ]
                ],
                [
                    'totalMoviments' => -1200,
                    'operationalFee' => 21.7,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_BUY,
                            'quantity' => 100,
                            'price' => 12,
                        ]
                    ]
                ],
                [
                    'totalMoviments' => 575,
                    'operationalFee' => 20.86,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_SELL,
                            'quantity' => 50,
                            'price' => 11.5,
                        ]
                    ]
                ],
                [
                    'totalMoviments' => -3000,
                    'operationalFee' => 23.6,
                    'operations' => [
                        [
                            'type' => Operation::TYPE_BUY,
                            'quantity' => 200,
                            'price' => 15,
                        ]
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Operation::TYPE_BUY,
                    'quantity' => 100,
                    'unitPrice' => 9.0,
                    'totalOperation' => 900.0,
                    'totalCosts' => 21.5,
                    'positionPrice' => 9.215,
                    'accumulatedQuantity' => 100,
                    'accumulatedTotal' => 900,
                    'accumulatedCosts' => 21.5,
                    'averagePrice' => 9.215,
                    'averagePriceToIr' => 9.215,
                ],
                [
                    'type' => Operation::TYPE_BUY,
                    'quantity' => 100,
                    'unitPrice' => 12.0,
                    'totalOperation' => 1200.0,
                    'totalCosts' => 21.7,
                    'positionPrice' => 12.217,
                    'accumulatedQuantity' => 200,
                    'accumulatedTotal' => 2100,
                    'accumulatedCosts' => 43.2,
                    'averagePrice' => 10.716,
                    'averagePriceToIr' => 10.716,
                ],
                [
                    'type' => Operation::TYPE_SELL,
                    'quantity' => 50,
                    'unitPrice' => 11.5,
                    'totalOperation' => 575.0,
                    'totalCosts' => 20.86,
                    'positionPrice' => 11.0828,
                    'accumulatedQuantity' => 150,
                    'accumulatedTotal' => 1525.0,
                    'accumulatedCosts' => 22.34,
                    'averagePrice' => 10.7160,
                    'averagePriceToIr' => 10.716,
                ],
                [
                    'type' => Operation::TYPE_BUY,
                    'quantity' => 200,
                    'unitPrice' => 15.0,
                    'totalOperation' => 3000.0,
                    'totalCosts' => 23.6,
                    'positionPrice' => 15.118,
                    'accumulatedQuantity' => 350,
                    'accumulatedTotal' => 4525.0,
                    'accumulatedCosts' => 45.94,
                    'averagePrice' => 13.0598,
                    'averagePriceToIr' => 13.2314,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getBrokerageNotesToCalculatePositions
     * @param $brokerageNotes
     * @param $expected
     */
    public function testCalculatePositions_ShouldCalculatePositions($brokerageNotes, $expected): void
    {
        $brokers = $this->brokerService->getAll();
        /** @var Broker $broker */
        $broker = $brokers[$this->faker->numberBetween(0, count($brokers) -1)];

        $date = $this->faker->dateTime();

        $assets = $this->assetService->getAll();
        /** @var Asset $asset */
        $asset = $assets[$this->faker->numberBetween(0, count($assets) -1)];

        foreach($brokerageNotes as $key => $brokerageNote){
            $brokerageNoteEntity = $this->addBrokerageNote($broker, $date->modify("+$key day"), $brokerageNote['totalMoviments'], $brokerageNote['operationalFee']);

            foreach ($brokerageNote['operations'] as $operation){
                $this->addOperation($brokerageNoteEntity->getId(), $operation['type'], $asset->getId(), $operation['quantity'], $operation['price']);
            }
        }

        $positions = $this->positionService->getAll();

        /** @var Position $position */
        foreach ($positions as $key => $position){
            self::assertEquals($expected[$key]['type'], $position->getType());
            self::assertEquals($expected[$key]['quantity'], $position->getQuantity());
            self::assertEquals($expected[$key]['unitPrice'], $position->getUnitPrice());
            self::assertEquals($expected[$key]['totalOperation'], $position->getTotalOperation());
            self::assertEquals($expected[$key]['totalCosts'], $position->getTotalCosts());
            self::assertEquals($expected[$key]['positionPrice'], $position->getPositionPrice());
            self::assertEquals($expected[$key]['accumulatedQuantity'], $position->getAccumulatedQuantity());
            self::assertEquals($expected[$key]['accumulatedTotal'], $position->getAccumulatedTotal());
            self::assertEquals($expected[$key]['accumulatedCosts'], $position->getAccumulatedCosts());
            self::assertEquals($expected[$key]['averagePrice'], $position->getAveragePrice());
            self::assertEquals($expected[$key]['averagePriceToIr'], $position->getAveragePriceToIr());
        }
    }

    private function addBrokerageNote(Broker $broker, DateTime $date, float $totalMoviments, float $operationalFee): BrokerageNote
    {
        $brokerageNoteDTO = (new BrokerageNoteDTO())
            ->setBrokerId($broker->getId())
            ->setNumber($this->faker->numberBetween(1, 100_000))
            ->setDate($date->format('Y-m-d'))
            ->setTotalMoviments($totalMoviments)
            ->setOperationalFee($operationalFee)
            ->setRegistrationFee(.0)
            ->setEmolumentFee(.0)
            ->setIssPisCofins(.0)
            ->setNoteIrrfTax(.0);

        return $this->brokerageNoteService->add($brokerageNoteDTO);
    }

    private function addOperation(int $brokerageNoteId, string $type, int $assetId, int $quantity, float $price): void
    {
        $operationDTO = (new OperationDTO())
            ->setType($type)
            ->setAssetId($assetId)
            ->setQuantity($quantity)
            ->setPrice($price);

        $this->brokerageNoteService->addOperation($brokerageNoteId, $operationDTO);
    }
}