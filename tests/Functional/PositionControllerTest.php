<?php

namespace App\Tests\Functional;

use App\Entity\Asset;
use App\Entity\Broker;
use App\Entity\Operation;

class PositionControllerTest extends BaseTest
{
    private function createBrokerageNote(): array {
        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        return [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => -1_000,
            'operational_fee' => 1,
            'registration_fee' => 1,
            'emolument_fee' => 1,
            'iss_pis_cofins' => 1,
            'irrf_normal_tax' => 1,
            'irrf_daytrade_tax' => 1,
        ];
    }

    private function createOperation(): array
    {
        $asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([]);

        return [
            'type' => Operation::TYPE_BUY,
            'asset_id' => $asset->getId(),
            'quantity' => 5,
            'price' => 200,
        ];
    }

    public function testGetPositions_ShouldReturnSuccess(): void
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $brokerage_note_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $brokerage_note_request_body);
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $operation_request_body = json_encode($new_operation, JSON_THROW_ON_ERROR);
        $operation_response = $this->executeRequestWithToken('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], $operation_request_body);
        $operation_response_body = json_decode($operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $position_response = $this->executeRequestWithToken('GET', "/api/positions");
        $position_response_body = json_decode($position_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $brokerage_note_response->getStatusCode());
        self::assertNotEmpty($brokerage_note_response_body);

        self::assertEquals($status_code_expected, $operation_response->getStatusCode());
        self::assertNotEmpty($operation_response_body);

        self::assertNotEmpty($position_response_body['content']);
    }
}