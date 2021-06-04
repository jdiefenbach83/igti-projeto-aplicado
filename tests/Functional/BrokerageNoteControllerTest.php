<?php

namespace App\Tests\Functional;

use App\Entity\Asset;
use App\Entity\Broker;
use App\Entity\BrokerageNote;
use App\Entity\Operation;

class BrokerageNoteControllerTest extends BaseTest
{
    private function createBrokerageNote(): array {
        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        return [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => $this->faker->randomFloat(4, 50_000, 100_000),
            'operational_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'registration_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'emolument_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'iss_pis_cofins' => $this->faker->randomFloat(4, 1, 100_000),
            'irrf_normal_tax' => $this->faker->randomFloat(4, 1, 100_000),
            'irrf_daytrade_tax' => $this->faker->randomFloat(4, 1, 100_000),
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
            'quantity' => $this->faker->numberBetween(2, 99),
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }

    public function testAddBrokerageNote_ShouldReturnSuccess(): void
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $request_body = json_encode($new_brokerage_note);
        $response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $request_body);
        $response_body = json_decode($response->getContent(), true);
        $brokerage_note_id = $response_body['content']['id'];

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertEquals($new_brokerage_note['date'], $response_body['content']['date']);
        self::assertEquals($new_brokerage_note['number'], $response_body['content']['number']);
        self::assertEquals($new_brokerage_note['total_moviments'], $response_body['content']['total_moviments']);
        self::assertEquals($new_brokerage_note['operational_fee'], $response_body['content']['operational_fee']);
        self::assertEquals($new_brokerage_note['registration_fee'], $response_body['content']['registration_fee']);
        self::assertEquals($new_brokerage_note['emolument_fee'], $response_body['content']['emolument_fee']);
        self::assertEquals($new_brokerage_note['iss_pis_cofins'], $response_body['content']['iss_pis_cofins']);
        self::assertEquals($new_brokerage_note['irrf_normal_tax'], $response_body['content']['irrf_normal_tax']);
        self::assertEquals($new_brokerage_note['irrf_daytrade_tax'], $response_body['content']['irrf_daytrade_tax']);
        self::assertNotNull($brokerage_note);
        self::assertEquals($new_brokerage_note['date'], $brokerage_note->getDate()->format('Y-m-d'));
        self::assertEquals($new_brokerage_note['number'], $brokerage_note->getNumber());
        self::assertEquals($new_brokerage_note['total_moviments'], $brokerage_note->getTotalMoviments());
        self::assertEquals($new_brokerage_note['operational_fee'], $brokerage_note->getOperationalFee());
        self::assertEquals($new_brokerage_note['registration_fee'], $brokerage_note->getRegistrationFee());
        self::assertEquals($new_brokerage_note['emolument_fee'], $brokerage_note->getEmolumentFee());
        self::assertEquals($new_brokerage_note['iss_pis_cofins'], $brokerage_note->getIssPisCofins());
        self::assertEquals($new_brokerage_note['irrf_normal_tax'], $brokerage_note->getIrrfNormalTax());
        self::assertEquals($new_brokerage_note['irrf_daytrade_tax'], $brokerage_note->getIrrfDaytradeTax());
    }

    public function getInvalidValuesToCreateOrUpdateBrokerageNote(): iterable {
        yield 'broker_id - null' => [ 'broker_id', null ];
        yield 'broker_id - invalid' => [ 'broker_id', 123456 ];
        yield 'date - null' => [ 'date', null ];
        yield 'date - invalid' => [ 'date', '2020-20-20' ];
        yield 'number - null' => [ 'number', null ];
        yield 'number - invalid' => [ 'number', -123456 ];
        yield 'total_moviments - null' => [ 'total_moviments', null ];
        yield 'operational_fee - null' => [ 'operational_fee', null ];
        yield 'operational_fee - invalid' => [ 'operational_fee', -123456 ];
        yield 'registration_fee - null' => [ 'registration_fee', null ];
        yield 'registration_fee - invalid' => [ 'registration_fee', -123456 ];
        yield 'emolument_fee - null' => [ 'emolument_fee', null ];
        yield 'emolument_fee - invalid' => [ 'emolument_fee', -123456 ];
        yield 'iss_pis_cofins - null' => [ 'iss_pis_cofins', null ];
        yield 'iss_pis_cofins - invalid' => [ 'iss_pis_cofins', -123456 ];
        yield 'irrf_normal_tax - null' => [ 'irrf_normal_tax', null ];
        yield 'irrf_normal_tax - invalid' => [ 'irrf_normal_tax', -123456 ];
        yield 'irrf_daytrade_tax - null' => [ 'irrf_daytrade_tax', null ];
        yield 'irrf_daytrade_tax - invalid' => [ 'irrf_daytrade_tax', -123456 ];
    }

    /**
     * @dataProvider getInvalidValuesToCreateOrUpdateBrokerageNote
     * @param string $key
     * @param $value
     */
    public function testAddBrokerageNote_ShouldFailWhenCreate(string $key, $value): void
    {
        $status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_brokerage_note[$key] = $value;

        $request_body = json_encode($new_brokerage_note);
        $response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $request_body);
        $response_body = json_decode($response->getContent(), true);

        self::assertEquals($status_code_expected, $response->getStatusCode());
    }

    public function testUpdateBrokerareNote_ShouldReturnSuccess(): void
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 200;

        $brokerageNoteRepository = $this->entityManager
            ->getRepository(BrokerageNote::class);

        $new_brokerage_note = $this->createBrokerageNote();
        $new_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $new_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $new_request_body);
        $new_response_body = json_decode($new_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $new_brokerage_note_id = $new_response_body['content']['id'];

        $brokerage_note_to_update_entity = $brokerageNoteRepository->find($new_brokerage_note_id);

        $brokerage_note_to_update['broker_id'] = $brokerage_note_to_update_entity->getBroker()->getId();
        $brokerage_note_to_update['date'] = $this->faker->dateTime()->format('Y-m-d');
        $brokerage_note_to_update['number'] = $this->faker->numberBetween(1, 100_000);
        $brokerage_note_to_update['total_moviments'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['operational_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['registration_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['emolument_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['iss_pis_cofins'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['irrf_normal_tax'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['irrf_daytrade_tax'] = $this->faker->randomFloat(4, 1, 100_000);

        $request_body = json_encode($brokerage_note_to_update, JSON_THROW_ON_ERROR);

        $update_response = $this->executeRequestWithToken('PUT', "/api/brokerageNotes/$new_brokerage_note_id", [], $request_body);
        $update_response_body = json_decode($update_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->entityManager->clear(BrokerageNote::class);
        $updated_brokerage_note = $brokerageNoteRepository->find($new_brokerage_note_id);

        self::assertEquals($new_status_code_expected, $new_response->getStatusCode());
        self::assertEquals($update_status_code_expected, $update_response->getStatusCode());
        self::assertNotEmpty($update_response_body);
        self::assertEquals($updated_brokerage_note->getDate()->format('Y-m-d'), $update_response_body['content']['date']);
        self::assertEquals($updated_brokerage_note->getNumber(), $update_response_body['content']['number']);
        self::assertEquals($updated_brokerage_note->getTotalMoviments(), $update_response_body['content']['total_moviments']);
        self::assertEquals($updated_brokerage_note->getOperationalFee(), $update_response_body['content']['operational_fee']);
        self::assertEquals($updated_brokerage_note->getRegistrationFee(), $update_response_body['content']['registration_fee']);
        self::assertEquals($updated_brokerage_note->getEmolumentFee(), $update_response_body['content']['emolument_fee']);
        self::assertEquals($updated_brokerage_note->getIssPisCofins(), $update_response_body['content']['iss_pis_cofins']);
        self::assertEquals($updated_brokerage_note->getIrrfNormalTax(), $update_response_body['content']['irrf_normal_tax']);
        self::assertEquals($updated_brokerage_note->getIrrfDaytradeTax(), $update_response_body['content']['irrf_daytrade_tax']);
    }

    /**
     * @dataProvider getInvalidValuesToCreateOrUpdateBrokerageNote
     * @param string $key
     * @param $value
     */
    public function testUpdateBrokerageNote_ShouldFailWhenUpdate(string $key, $value): void
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();

        $new_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $new_response = $this->executeRequestWithToken('POST', "/api/brokerageNotes", [], $new_request_body);
        $new_response_body = json_decode($new_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $brokerage_note_to_update_entity = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy([]);

        $brokerage_note_to_update[$key] = $value;

        $request_body = json_encode($brokerage_note_to_update, JSON_THROW_ON_ERROR);
        $update_response = $this->executeRequestWithToken('PUT', "/api/brokerageNotes/{$brokerage_note_to_update_entity->getId()}", [], $request_body);
        $update_response_body = json_decode($update_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($new_status_code_expected, $new_response->getStatusCode());
        self::assertEquals($update_status_code_expected, $update_response->getStatusCode());
    }

    public function testUpdateBroker_ShouldReturnNotFound(): void
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $response = $this->executeRequestWithToken('PUT', "/api/brokerageNotes/$id");
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }

    public function testGetAllBrokerageNotes(): void
    {
        $status_code_expected = 200;

        $response = $this->executeRequestWithToken('GET', '/api/brokerageNotes');
        $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
    }

    public function testGetBrokerageNoteById_ShouldReturnSucess(): void
    {
        $new_status_code_expected = 201;
        $get_by_id_status_code_expected = 200;

        $new_brokerage_note = $this->createBrokerageNote();

        $new_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $new_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $new_request_body);
        $new_response_body = json_decode($new_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $get_by_id_response = $this->executeRequestWithToken('GET', "/api/brokerageNotes/{$new_response_body['content']['id']}", [], $new_request_body);
        $get_by_id_response_body = json_decode($get_by_id_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($new_status_code_expected, $new_response->getStatusCode());
        self::assertEquals($get_by_id_status_code_expected, $get_by_id_response->getStatusCode());
        self::assertNotEmpty($get_by_id_response_body);
        self::assertEquals($get_by_id_response_body['content']['id'], $new_response_body['content']['id']);
    }

    public function testGetBrokerageNoteById_ShouldReturnNoContent(): void
    {
        $status_code_expected = 204;

        $response = $this->executeRequestWithToken('GET', '/api/brokerageNotes/-1');
        $response_body = json_decode($response->getContent(), true);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEmpty($response_body);
    }

    public function testRemoveBrokerageNote_ShouldReturnSuccess(): void
    {
        $new_status_code_expected = 201;
        $remove_status_code_expected = 204;

        $new_brokerage_note = $this->createBrokerageNote();

        $new_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $new_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $new_request_body);
        $new_response_body = json_decode($new_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $brokerage_note_id = $new_response_body['content']['id'];

        $remove_response = $this->executeRequestWithToken('DELETE', "/api/brokerageNotes/$brokerage_note_id");

        $removed_brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        self::assertEquals($new_status_code_expected, $new_response->getStatusCode());
        self::assertEquals($remove_status_code_expected, $remove_response->getStatusCode());
        self::assertNull($removed_brokerage_note);
    }

    public function testRemoveBrokerageNote_ShouldReturnNotFound(): void
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $response = $this->executeRequestWithToken('DELETE', "/api/brokerageNotes/$id");
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }

    public function testAddOperationIntoBrokerageNote_ShouldReturnSuccess(): void
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

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        self::assertEquals($status_code_expected, $brokerage_note_response->getStatusCode());
        self::assertNotEmpty($brokerage_note_response_body);

        self::assertEquals($status_code_expected, $operation_response->getStatusCode());
        self::assertNotEmpty($operation_response_body);

        self::assertEquals($new_operation['type'], $operation_response_body['content']['type']);
        self::assertEquals($new_operation['asset_id'], $operation_response_body['content']['asset_id']);
        self::assertEquals($new_operation['quantity'], $operation_response_body['content']['quantity']);
        self::assertEquals($new_operation['price'], $operation_response_body['content']['price']);

        self::assertNotNull($brokerage_note);
        self::assertEquals($new_operation['type'], $brokerage_note->getOperations()[0]->getType());
        self::assertEquals($new_operation['asset_id'], $brokerage_note->getOperations()[0]->getAsset()->getId());
        self::assertEquals($new_operation['quantity'], $brokerage_note->getOperations()[0]->getQuantity());
        self::assertEquals($new_operation['price'], $brokerage_note->getOperations()[0]->getPrice());

        self::assertEquals($brokerage_note->getTotalOperations(), $brokerage_note->getOperations()[0]->getTotalForCalculations());
    }

    public function getInvalidValuesToCreateOrUpdateOperationsIntoBrokerageNote(): iterable {
        yield 'type - null' => [ 'type', null ];
        yield 'type - invalid' => [ 'type', 'ABCD1234' ];
        yield 'asset_id - null' => [ 'asset_id', null ];
        yield 'asset_id - invalid' => [ 'asset_id', 123456 ];
        yield 'quantity - null' => [ 'quantity', null ];
        yield 'quantity - invalid' => [ 'quantity', -123456 ];
        yield 'price - null' => [ 'price', null ];
        yield 'price - invalid' => [ 'price', -123456 ];
    }

    /**
     * @dataProvider getInvalidValuesToCreateOrUpdateOperationsIntoBrokerageNote
     * @param string $key
     * @param $value
     */
    public function testAddOperationIntoBrokerageNote_ShouldFailWhenCreate(string $key, $value): void
    {
        $status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $brokerage_note_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $brokerage_note_request_body);
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation[$key] = $value;
        $operation_request_body = json_encode($new_operation, JSON_THROW_ON_ERROR);
        $operation_response = $this->executeRequestWithToken('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], $operation_request_body);
        $operation_response_body = json_decode($operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $operation_response->getStatusCode());
    }

    public function testUpdateOperationIntoBrokerareNote_ShouldReturnSuccess(): void
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 200;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $new_brokerage_note_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $new_request_body);
        $new_brokegare_note_response_body = json_decode($new_brokerage_note_response->getContent(), true);
        $new_brokerage_note_id = $new_brokegare_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation, JSON_THROW_ON_ERROR);
        $new_operation_response = $this->executeRequestWithToken('POST', "/api/brokerageNotes/$new_brokerage_note_id/operations", [], $new_operation_request_body);
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $update_operation = $this->createOperation();
        $update_operation_request_body = json_encode($update_operation, JSON_THROW_ON_ERROR);
        $update_operation_response = $this->executeRequestWithToken('PUT', "/api/brokerageNotes/$new_brokerage_note_id/operations/$new_operation_line", [], $update_operation_request_body);
        $update_operation_response_body = json_decode($update_operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($new_brokerage_note_id);

        self::assertEquals($new_status_code_expected, $new_brokerage_note_response->getStatusCode());
        self::assertNotEmpty($new_brokegare_note_response_body);

        self::assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        self::assertNotEmpty($new_operation_response_body);

        self::assertEquals($update_status_code_expected, $update_operation_response->getStatusCode());
        self::assertNotEmpty($update_operation_response_body);

        self::assertEquals($update_operation['type'], $update_operation_response_body['content']['type']);
        self::assertEquals($update_operation['asset_id'], $update_operation_response_body['content']['asset_id']);
        self::assertEquals($update_operation['quantity'], $update_operation_response_body['content']['quantity']);
        self::assertEquals($update_operation['price'], $update_operation_response_body['content']['price']);

        self::assertNotNull($brokerage_note);

        self::assertEquals($update_operation['type'], $brokerage_note->getOperations()[0]->getType());
        self::assertEquals($update_operation['asset_id'], $brokerage_note->getOperations()[0]->getAsset()->getId());
        self::assertEquals($update_operation['quantity'], $brokerage_note->getOperations()[0]->getQuantity());
        self::assertEquals($update_operation['price'], $brokerage_note->getOperations()[0]->getPrice());
    }

    /**
     * @dataProvider getInvalidValuesToCreateOrUpdateOperationsIntoBrokerageNote
     * @param string $key
     * @param $value
     */
    public function testUpdateOperationIntoBrokerageNote_ShouldFailWhenUpdate(string $key, $value): void
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $new_brokerage_note_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $new_request_body);
        $new_brokegare_note_response_body = json_decode($new_brokerage_note_response->getContent(), true);
        $new_brokerage_note_id = $new_brokegare_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation, JSON_THROW_ON_ERROR);
        $new_operation_response = $this->executeRequestWithToken('POST', "/api/brokerageNotes/$new_brokerage_note_id/operations", [], $new_operation_request_body);
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $update_operation = $this->createOperation();
        $update_operation[$key] = $value;
        $update_operation_request_body = json_encode($update_operation, JSON_THROW_ON_ERROR);
        $update_operation_response = $this->executeRequestWithToken('PUT', "/api/brokerageNotes/$new_brokerage_note_id/operations/$new_operation_line", [], $update_operation_request_body);
        $update_operation_response_body = json_decode($update_operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($new_status_code_expected, $new_brokerage_note_response->getStatusCode());
        self::assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        self::assertEquals($update_status_code_expected, $update_operation_response->getStatusCode());
    }

    public function testRemoveOperationFromBrokerageNote_ShouldReturnSuccess(): void
    {
        $new_status_code_expected = 201;
        $remove_status_code_expected = 204;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $brokerage_note_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $brokerage_note_request_body);
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation, JSON_THROW_ON_ERROR);
        $new_operation_response = $this->executeRequestWithToken('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], $new_operation_request_body);
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $remove_operation_response = $this->executeRequestWithToken('DELETE', "/api/brokerageNotes/$brokerage_note_id/operations/$new_operation_line");

        $removed_operation_from_brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        self::assertEquals($new_status_code_expected, $brokerage_note_response->getStatusCode());
        self::assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        self::assertEquals($remove_status_code_expected, $remove_operation_response->getStatusCode());
        self::assertEmpty($removed_operation_from_brokerage_note->getOperations());
    }

    public function testRemoveOperationFromBrokerageNote_ShouldReturnNotFound(): void
    {
        $new_status_code_expected = 201;
        $status_code_expected = 404;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $brokerage_note_response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $brokerage_note_request_body);
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $line = $this->faker->numberBetween(1000000, 2000000);
        $response = $this->executeRequestWithToken('DELETE', "/api/brokerageNotes/$brokerage_note_id/operations/$line");
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($new_status_code_expected, $brokerage_note_response->getStatusCode());
        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }

    public function testAddBrokerageNote_ShouldCalculateCorretly(): void
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_brokerage_note['total_moviments'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['operational_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['registration_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['emolument_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['iss_pis_cofins'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['irrf_normal_tax'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['irrf_daytrade_tax'] = $this->faker->randomFloat(4, 1, 100_000);

        $request_body = json_encode($new_brokerage_note, JSON_THROW_ON_ERROR);
        $response = $this->executeRequestWithToken('POST', '/api/brokerageNotes', [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $total_fees = bcadd($new_brokerage_note['operational_fee'], $new_brokerage_note['registration_fee'], 4);
        $total_fees = bcadd($total_fees, $new_brokerage_note['emolument_fee'], 4);

        $total_costs = bcadd($total_fees, $new_brokerage_note['iss_pis_cofins'], 4);
        $total_costs = bcadd($total_costs, $new_brokerage_note['irrf_normal_tax'], 4);
        $total_costs = bcadd($total_costs, $new_brokerage_note['irrf_daytrade_tax'], 4);

        $net_total = bcsub($new_brokerage_note['total_moviments'], $total_costs, 4);

        $result = bcsub($new_brokerage_note['total_moviments'], $total_fees, 4);
        $result = bcsub($result, $new_brokerage_note['iss_pis_cofins'], 4);
        $result = bcsub($result, $new_brokerage_note['irrf_normal_tax'], 4);
        $result = bcsub($result, $new_brokerage_note['irrf_daytrade_tax'], 4);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEquals($total_fees, $brokerage_note->getTotalFees());
        self::assertEquals($total_costs, $brokerage_note->getTotalCosts());
        self::assertEquals($net_total, $brokerage_note->getNetTotal());
        self::assertEquals($result, $brokerage_note->getResult());
    }
}