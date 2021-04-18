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
                            'type' => Position::TYPE_BUY,
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
                            'type' => Position::TYPE_BUY,
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
                            'type' => Position::TYPE_BUY,
                            'quantity' => 200,
                            'price' => 15,
                        ]
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 100,
                ],
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 100,
                ],
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 200,
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
                            'type' => Position::TYPE_BUY,
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
                            'type' => Position::TYPE_BUY,
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
                            'type' => Position::TYPE_SELL,
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
                            'type' => Position::TYPE_BUY,
                            'quantity' => 200,
                            'price' => 15,
                        ]
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 50,
                ],
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 100,
                ],
                [
                    'type' => Position::TYPE_SELL,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => 18.34,
                    'accumulatedResult' => 18.34,
                    'quantityBalance' => 0,
                ],
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
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
                    'result' => .0,
                    'accumulatedResult' => 18.34,
                    'quantityBalance' => 200,
                ],
            ],
        ];

        yield 'Daytrade with balance' => [
            'brokerageNotes' => [
                [
                    'totalMoviments' => -189.9,
                    'operationalFee' => 5.56,
                    'operations' => [
                        [
                            'type' => Position::TYPE_BUY,
                            'quantity' => 3,
                            'price' => 94.30,
                        ],
                        [
                            'type' => Position::TYPE_BUY,
                            'quantity' => 1,
                            'price' => 94.30,
                        ],
                        [
                            'type' => Position::TYPE_BUY,
                            'quantity' => 2,
                            'price' => 94.39,
                        ],
                        [
                            'type' => Position::TYPE_SELL,
                            'quantity' => 4,
                            'price' => 94.02,
                        ],
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 3,
                    'unitPrice' => 94.3,
                    'totalOperation' => 282.9,
                    'totalCosts' => 1.668,
                    'positionPrice' => 94.856,
                    'accumulatedQuantity' => 3,
                    'accumulatedTotal' => 282.9,
                    'accumulatedCosts' => 1.668,
                    'averagePrice' => 94.856,
                    'averagePriceToIr' => 94.856,
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 0,
                ],
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 1,
                    'unitPrice' => 94.3,
                    'totalOperation' => 94.3,
                    'totalCosts' => 0.556,
                    'positionPrice' => 94.856,
                    'accumulatedQuantity' => 4,
                    'accumulatedTotal' => 377.2,
                    'accumulatedCosts' => 2.224,
                    'averagePrice' => 94.856,
                    'averagePriceToIr' => 94.856,
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 0,
                ],
                [
                    'type' => Position::TYPE_SELL,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 4,
                    'unitPrice' => 94.02,
                    'totalOperation' => 376.08,
                    'totalCosts' => 2.224,
                    'positionPrice' => 93.464,
                    'accumulatedQuantity' => 0,
                    'accumulatedTotal' => .0,
                    'accumulatedCosts' => .0,
                    'averagePrice' => 94.8560,
                    'averagePriceToIr' => 94.8560,
                    'result' => -5.568,
                    'accumulatedResult' => -5.568,
                    'quantityBalance' => 0,
                ],
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_NORMAL,
                    'quantity' => 2,
                    'unitPrice' => 94.39,
                    'totalOperation' => 188.78,
                    'totalCosts' => 1.112,
                    'positionPrice' => 94.946,
                    'accumulatedQuantity' => 2,
                    'accumulatedTotal' => 188.78,
                    'accumulatedCosts' => 1.112,
                    'averagePrice' => 94.946,
                    'averagePriceToIr' => 94.946,
                    'result' => .0,
                    'accumulatedResult' => -5.568,
                    'quantityBalance' => 2,
                ],
            ],
        ];

        yield 'Daytrade zero balance - first buy then sell' => [
            'brokerageNotes' => [
                [
                    'totalMoviments' => 100.0,
                    'operationalFee' => 1.70,
                    'operations' => [
                        [
                            'type' => Position::TYPE_BUY,
                            'quantity' => 5,
                            'price' => 20.0,
                        ],
                        [
                            'type' => Position::TYPE_SELL,
                            'quantity' => 5,
                            'price' => 40.0,
                        ],
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 5,
                    'unitPrice' => 20.0,
                    'totalOperation' => 100.0,
                    'totalCosts' => 0.85,
                    'positionPrice' => 20.17,
                    'accumulatedQuantity' => 5,
                    'accumulatedTotal' => 100,
                    'accumulatedCosts' => 0.85,
                    'averagePrice' => 20.17,
                    'averagePriceToIr' => 20.17,
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 0,
                ],
                [
                    'type' => Position::TYPE_SELL,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 5,
                    'unitPrice' => 40.0,
                    'totalOperation' => 200.0,
                    'totalCosts' => 0.85,
                    'positionPrice' => 39.83,
                    'accumulatedQuantity' => 0,
                    'accumulatedTotal' => 0,
                    'accumulatedCosts' => 0,
                    'averagePrice' => 20.17,
                    'averagePriceToIr' => 20.17,
                    'result' => 98.30,
                    'accumulatedResult' => 98.30,
                    'quantityBalance' => 0,
                ],
            ],
        ];

        yield 'Daytrade zero balance - first sell then buy' => [
            'brokerageNotes' => [
                [
                    'totalMoviments' => -100.0,
                    'operationalFee' => 10.84,
                    'operations' => [
                        [
                            'type' => Position::TYPE_SELL,
                            'quantity' => 10,
                            'price' => 10.0,
                        ],
                        [
                            'type' => Position::TYPE_BUY,
                            'quantity' => 10,
                            'price' => 20.0,
                        ],
                    ]
                ],
            ],
            'expected' => [
                [
                    'type' => Position::TYPE_BUY,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 10,
                    'unitPrice' => 20.0,
                    'totalOperation' => 200.0,
                    'totalCosts' => 5.42,
                    'positionPrice' => 20.542,
                    'accumulatedQuantity' => 10,
                    'accumulatedTotal' => 200,
                    'accumulatedCosts' => 5.42,
                    'averagePrice' => 20.542,
                    'averagePriceToIr' => 20.542,
                    'result' => .0,
                    'accumulatedResult' => .0,
                    'quantityBalance' => 0,
                ],
                [
                    'type' => Position::TYPE_SELL,
                    'negotiationType' => Position::NEGOTIATION_TYPE_DAYTRADE,
                    'quantity' => 10,
                    'unitPrice' => 10.0,
                    'totalOperation' => 100.0,
                    'totalCosts' => 5.42,
                    'positionPrice' => 9.458,
                    'accumulatedQuantity' => 0,
                    'accumulatedTotal' => 0,
                    'accumulatedCosts' => 0,
                    'averagePrice' => 20.542,
                    'averagePriceToIr' => 20.542,
                    'result' => -110.84,
                    'accumulatedResult' => -110.84,
                    'quantityBalance' => 0,
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
            self::assertEquals($expected[$key]['negotiationType'], $position->getNegotiationType());
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
            self::assertEquals($expected[$key]['result'], $position->getResult());
            self::assertEquals($expected[$key]['accumulatedResult'], $position->getAccumulatedResult());
            self::assertEquals($expected[$key]['quantityBalance'], $position->getQuantityBalance());
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