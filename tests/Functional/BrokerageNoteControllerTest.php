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
            'note_irrf_tax' => $this->faker->randomFloat(4, 1, 100_000),
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

    public function testAddBrokerageNote_ShouldReturnSuccess()
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $request_body);
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);
        $brokerage_note_id = $response_body['content']['id'];

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($new_brokerage_note['date'], $response_body['content']['date']);
        $this->assertEquals($new_brokerage_note['number'], $response_body['content']['number']);
        $this->assertEquals($new_brokerage_note['total_moviments'], $response_body['content']['total_moviments']);
        $this->assertEquals($new_brokerage_note['operational_fee'], $response_body['content']['operational_fee']);
        $this->assertEquals($new_brokerage_note['registration_fee'], $response_body['content']['registration_fee']);
        $this->assertEquals($new_brokerage_note['emolument_fee'], $response_body['content']['emolument_fee']);
        $this->assertEquals($new_brokerage_note['iss_pis_cofins'], $response_body['content']['iss_pis_cofins']);
        $this->assertEquals($new_brokerage_note['note_irrf_tax'], $response_body['content']['note_irrf_tax']);
        $this->assertNotNull($brokerage_note);
        $this->assertEquals($new_brokerage_note['date'], $brokerage_note->getDate()->format('Y-m-d'));
        $this->assertEquals($new_brokerage_note['number'], $brokerage_note->getNumber());
        $this->assertEquals($new_brokerage_note['total_moviments'], $brokerage_note->getTotalMoviments());
        $this->assertEquals($new_brokerage_note['operational_fee'], $brokerage_note->getOperationalFee());
        $this->assertEquals($new_brokerage_note['registration_fee'], $brokerage_note->getRegistrationFee());
        $this->assertEquals($new_brokerage_note['emolument_fee'], $brokerage_note->getEmolumentFee());
        $this->assertEquals($new_brokerage_note['iss_pis_cofins'], $brokerage_note->getIssPisCofins());
        $this->assertEquals($new_brokerage_note['note_irrf_tax'], $brokerage_note->getNoteIrrfTax());
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
        yield 'note_irrf_tax - null' => [ 'note_irrf_tax', null ];
        yield 'note_irrf_tax - invalid' => [ 'note_irrf_tax', -123456 ];
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

        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
    }

    public function testUpdateBrokerareNote_ShouldReturnSuccess()
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 200;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);
        $new_response = $this->client->getResponse();
        $new_response_body = json_decode($new_response->getContent(), true);
        $new_brokerage_note_id = $new_response_body['content']['id'];

        $brokerage_note_to_update_entity = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($new_brokerage_note_id);

        $brokerage_note_to_update['broker_id'] = $brokerage_note_to_update_entity->getBroker()->getId();
        $brokerage_note_to_update['date'] = $this->faker->dateTime()->format('Y-m-d');
        $brokerage_note_to_update['number'] = $this->faker->numberBetween(1, 100_000);
        $brokerage_note_to_update['total_moviments'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['operational_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['registration_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['emolument_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['iss_pis_cofins'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['note_irrf_tax'] = $this->faker->randomFloat(4, 1, 100_000);

        $request_body = json_encode($brokerage_note_to_update);

        $this->client->request('PUT', "/api/brokerageNotes/$new_brokerage_note_id", [], [], [], $request_body);

        $update_response = $this->client->getResponse();
        $update_response_body = json_decode($update_response->getContent(), true);

        $updated_brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy(['id' => $update_response_body['content']['id']]);

        $this->assertEquals($new_status_code_expected, $new_response->getStatusCode());
        $this->assertEquals($update_status_code_expected, $update_response->getStatusCode());
        $this->assertNotEmpty($update_response_body);
        $this->assertEquals($updated_brokerage_note->getDate()->format('Y-m-d'), $update_response_body['content']['date']);
        $this->assertEquals($updated_brokerage_note->getNumber(), $update_response_body['content']['number']);
        $this->assertEquals($updated_brokerage_note->getTotalMoviments(), $update_response_body['content']['total_moviments']);
        $this->assertEquals($updated_brokerage_note->getOperationalFee(), $update_response_body['content']['operational_fee']);
        $this->assertEquals($updated_brokerage_note->getRegistrationFee(), $update_response_body['content']['registration_fee']);
        $this->assertEquals($updated_brokerage_note->getEmolumentFee(), $update_response_body['content']['emolument_fee']);
        $this->assertEquals($updated_brokerage_note->getIssPisCofins(), $update_response_body['content']['iss_pis_cofins']);
        $this->assertEquals($updated_brokerage_note->getNoteIrrfTax(), $update_response_body['content']['note_irrf_tax']);
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

        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);

        $new_response = $this->client->getResponse();
        $new_response_body = json_decode($new_response->getContent(), true);

        $brokerage_note_to_update_entity = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy([]);

        $brokerage_note_to_update[$key] = $value;

        $request_body = json_encode($brokerage_note_to_update);

        $this->client->request('PUT', "/api/brokerageNotes/{$brokerage_note_to_update_entity->getId()}", [], [], [], $request_body);

        $update_response = $this->client->getResponse();
        $update_response_body = json_decode($update_response->getContent(), true);

        $this->assertEquals($new_status_code_expected, $new_response->getStatusCode());
        $this->assertEquals($update_status_code_expected, $update_response->getStatusCode());
    }

    public function testUpdateBroker_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('PUT', "/api/brokerageNotes/{$id}");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }

    public function testGetAllBrokerageNotes()
    {
        $status_code_expected = 200;

        $this->client->request('GET', '/api/brokerageNotes');
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
    }

    public function testGetBrokerageNoteById_ShouldReturnSucess()
    {
        $new_status_code_expected = 201;
        $get_by_id_status_code_expected = 200;

        $new_brokerage_note = $this->createBrokerageNote();

        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);

        $new_response = $this->client->getResponse();
        $new_response_body = json_decode($new_response->getContent(), true);

        $this->client->request('GET', "/api/brokerageNotes/{$new_response_body['content']['id']}");
        $get_by_id_response = $this->client->getResponse();
        $get_by_id_response_body = json_decode($get_by_id_response->getContent(), true);

        $this->assertEquals($new_status_code_expected, $new_response->getStatusCode());
        $this->assertEquals($get_by_id_status_code_expected, $get_by_id_response->getStatusCode());
        $this->assertNotEmpty($get_by_id_response_body);
        $this->assertEquals($get_by_id_response_body['content']['id'], $new_response_body['content']['id']);
    }

    public function testGetBrokerageNoteById_ShouldReturnNoContent()
    {
        $status_code_expected = 204;

        $this->client->request('GET', '/api/brokerageNotes/-1');
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEmpty($response_body);
    }

    public function testRemoveBrokerageNote_ShouldReturnSuccess()
    {
        $new_status_code_expected = 201;
        $remove_status_code_expected = 204;

        $new_brokerage_note = $this->createBrokerageNote();

        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);

        $new_response = $this->client->getResponse();
        $new_response_body = json_decode($new_response->getContent(), true);
        $brokerage_note_id = $new_response_body['content']['id'];

        $this->client->request('DELETE', "/api/brokerageNotes/$brokerage_note_id");
        $remove_response = $this->client->getResponse();

        $removed_brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        $this->assertEquals($new_status_code_expected, $new_response->getStatusCode());
        $this->assertEquals($remove_status_code_expected, $remove_response->getStatusCode());
        $this->assertNull($removed_brokerage_note);
    }

    public function testRemoveBrokerageNote_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('DELETE', "/api/brokerageNotes/{$id}");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }

    public function testAddOperationIntoBrokerageNote_ShouldReturnSuccess()
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $brokerage_note_request_body);
        $brokerage_note_response = $this->client->getResponse();
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], [], [], $operation_request_body);
        $operation_response = $this->client->getResponse();
        $operation_response_body = json_decode($operation_response->getContent(), true);

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        $this->assertEquals($status_code_expected, $brokerage_note_response->getStatusCode());
        $this->assertNotEmpty($brokerage_note_response_body);

        $this->assertEquals($status_code_expected, $operation_response->getStatusCode());
        $this->assertNotEmpty($operation_response_body);

        $this->assertEquals($new_operation['type'], $operation_response_body['content']['type']);
        $this->assertEquals($new_operation['asset_id'], $operation_response_body['content']['asset_id']);
        $this->assertEquals($new_operation['quantity'], $operation_response_body['content']['quantity']);
        $this->assertEquals($new_operation['price'], $operation_response_body['content']['price']);

        $this->assertNotNull($brokerage_note);
        $this->assertEquals($new_operation['type'], $brokerage_note->getOperations()[0]->getType());
        $this->assertEquals($new_operation['asset_id'], $brokerage_note->getOperations()[0]->getAsset()->getId());
        $this->assertEquals($new_operation['quantity'], $brokerage_note->getOperations()[0]->getQuantity());
        $this->assertEquals($new_operation['price'], $brokerage_note->getOperations()[0]->getPrice());

        $this->assertEquals($brokerage_note->getTotalOperations(), $brokerage_note->getOperations()[0]->getTotalForCalculations());
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
        $brokerage_note_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $brokerage_note_request_body);
        $brokerage_note_response = $this->client->getResponse();
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation[$key] = $value;
        $operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], [], [], $operation_request_body);
        $operation_response = $this->client->getResponse();
        $operation_response_body = json_decode($operation_response->getContent(), true);

        $this->assertEquals($status_code_expected, $operation_response->getStatusCode());
    }

    public function testAddOperationIntoBrokerageNote_ShouldFailWhenCreateWithTotalOperationsGreaterThanTotalMoviments(): void
    {
        $status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $brokerage_note_request_body);
        $brokerage_note_response = $this->client->getResponse();
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation['price'] = $new_brokerage_note["total_moviments"];

        $operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], [], [], $operation_request_body);
        $operation_response = $this->client->getResponse();
        $operation_response_body = json_decode($operation_response->getContent(), true);

        $this->assertEquals($status_code_expected, $operation_response->getStatusCode());
    }

    public function testUpdateOperationIntoBrokerareNote_ShouldReturnSuccess()
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 200;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);
        $new_brokerage_note_response = $this->client->getResponse();
        $new_brokegare_note_response_body = json_decode($new_brokerage_note_response->getContent(), true);
        $new_brokerage_note_id = $new_brokegare_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$new_brokerage_note_id/operations", [], [], [], $new_operation_request_body);
        $new_operation_response = $this->client->getResponse();
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $update_operation = $this->createOperation();
        $update_operation_request_body = json_encode($update_operation);
        $this->client->request('PUT', "/api/brokerageNotes/$new_brokerage_note_id/operations/$new_operation_line", [], [], [], $update_operation_request_body);
        $update_operation_response = $this->client->getResponse();
        $update_operation_response_body = json_decode($update_operation_response->getContent(), true);

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($new_brokerage_note_id);

        $this->assertEquals($new_status_code_expected, $new_brokerage_note_response->getStatusCode());
        $this->assertNotEmpty($new_brokegare_note_response_body);

        $this->assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        $this->assertNotEmpty($new_operation_response_body);

        $this->assertEquals($update_status_code_expected, $update_operation_response->getStatusCode());
        $this->assertNotEmpty($update_operation_response_body);

        $this->assertEquals($update_operation['type'], $update_operation_response_body['content']['type']);
        $this->assertEquals($update_operation['asset_id'], $update_operation_response_body['content']['asset_id']);
        $this->assertEquals($update_operation['quantity'], $update_operation_response_body['content']['quantity']);
        $this->assertEquals($update_operation['price'], $update_operation_response_body['content']['price']);

        $this->assertNotNull($brokerage_note);

        $this->assertEquals($update_operation['type'], $brokerage_note->getOperations()[0]->getType());
        $this->assertEquals($update_operation['asset_id'], $brokerage_note->getOperations()[0]->getAsset()->getId());
        $this->assertEquals($update_operation['quantity'], $brokerage_note->getOperations()[0]->getQuantity());
        $this->assertEquals($update_operation['price'], $brokerage_note->getOperations()[0]->getPrice());
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
        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);
        $new_brokerage_note_response = $this->client->getResponse();
        $new_brokegare_note_response_body = json_decode($new_brokerage_note_response->getContent(), true);
        $new_brokerage_note_id = $new_brokegare_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$new_brokerage_note_id/operations", [], [], [], $new_operation_request_body);
        $new_operation_response = $this->client->getResponse();
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $update_operation = $this->createOperation();
        $update_operation[$key] = $value;
        $update_operation_request_body = json_encode($update_operation);
        $this->client->request('PUT', "/api/brokerageNotes/$new_brokerage_note_id/operations/$new_operation_line", [], [], [], $update_operation_request_body);
        $update_operation_response = $this->client->getResponse();
        $update_operation_response_body = json_decode($update_operation_response->getContent(), true);

        $this->assertEquals($new_status_code_expected, $new_brokerage_note_response->getStatusCode());
        $this->assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        $this->assertEquals($update_status_code_expected, $update_operation_response->getStatusCode());
    }

    public function testUpdateOperationIntoBrokerageNote_ShouldFailWhenUpdateWithTotalOperationsGreaterThanTotalMoviments(): void
    {
        $new_status_code_expected = 201;
        $update_status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $new_request_body);
        $new_brokerage_note_response = $this->client->getResponse();
        $new_brokegare_note_response_body = json_decode($new_brokerage_note_response->getContent(), true);
        $new_brokerage_note_id = $new_brokegare_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$new_brokerage_note_id/operations", [], [], [], $new_operation_request_body);
        $new_operation_response = $this->client->getResponse();
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $update_operation = $this->createOperation();
        $update_operation['price'] = $new_brokerage_note["total_moviments"];
        $update_operation_request_body = json_encode($update_operation);
        $this->client->request('PUT', "/api/brokerageNotes/$new_brokerage_note_id/operations/$new_operation_line", [], [], [], $update_operation_request_body);
        $update_operation_response = $this->client->getResponse();
        $update_operation_response_body = json_decode($update_operation_response->getContent(), true);

        $this->assertEquals($new_status_code_expected, $new_brokerage_note_response->getStatusCode());
        $this->assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        $this->assertEquals($update_status_code_expected, $update_operation_response->getStatusCode());
    }

    public function testRemoveOperationFromBrokerageNote_ShouldReturnSuccess()
    {
        $new_status_code_expected = 201;
        $remove_status_code_expected = 204;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $brokerage_note_request_body);
        $brokerage_note_response = $this->client->getResponse();
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $new_operation = $this->createOperation();
        $new_operation_request_body = json_encode($new_operation);
        $this->client->request('POST', "/api/brokerageNotes/$brokerage_note_id/operations", [], [], [], $new_operation_request_body);
        $new_operation_response = $this->client->getResponse();
        $new_operation_response_body = json_decode($new_operation_response->getContent(), true);
        $new_operation_line = $new_operation_response_body['content']['line'];

        $this->client->request('DELETE', "/api/brokerageNotes/$brokerage_note_id/operations/$new_operation_line");
        $remove_operation_response = $this->client->getResponse();

        $removed_operation_from_brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($brokerage_note_id);

        $this->assertEquals($new_status_code_expected, $brokerage_note_response->getStatusCode());
        $this->assertEquals($new_status_code_expected, $new_operation_response->getStatusCode());
        $this->assertEquals($remove_status_code_expected, $remove_operation_response->getStatusCode());
        $this->assertEmpty($removed_operation_from_brokerage_note->getOperations());
    }

    public function testRemoveOperationFromBrokerageNote_ShouldReturnNotFound()
    {
        $new_status_code_expected = 201;
        $status_code_expected = 404;

        $new_brokerage_note = $this->createBrokerageNote();
        $brokerage_note_request_body = json_encode($new_brokerage_note);
        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $brokerage_note_request_body);
        $brokerage_note_response = $this->client->getResponse();
        $brokerage_note_response_body = json_decode($brokerage_note_response->getContent(), true);
        $brokerage_note_id = $brokerage_note_response_body['content']['id'];

        $line = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('DELETE', "/api/brokerageNotes/$brokerage_note_id/operations/$line");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($new_status_code_expected, $brokerage_note_response->getStatusCode());
        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }

    public function testAddBrokerageNote_ShouldCalculateCorretly()
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_brokerage_note['total_moviments'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['operational_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['registration_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['emolument_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['iss_pis_cofins'] = $this->faker->randomFloat(4, 1, 100_000);
        $new_brokerage_note['note_irrf_tax'] = $this->faker->randomFloat(4, 1, 100_000);

        $request_body = json_encode($new_brokerage_note);

        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $total_fees = bcadd($new_brokerage_note['operational_fee'], $new_brokerage_note['registration_fee'], 4);
        $total_fees = bcadd($total_fees, $new_brokerage_note['emolument_fee'], 4);

        $total_costs = bcadd($total_fees, $new_brokerage_note['iss_pis_cofins'], 4);
        $total_costs = bcadd($total_costs, $new_brokerage_note['note_irrf_tax'], 4);

        $net_total = bcsub($new_brokerage_note['total_moviments'], $total_costs, 4);

        $result = bcsub($new_brokerage_note['total_moviments'], $total_fees, 4);
        $result = bcsub($result, $new_brokerage_note['iss_pis_cofins'], 4);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($total_fees, $brokerage_note->getTotalFees());
        $this->assertEquals($total_costs, $brokerage_note->getTotalCosts());
        $this->assertEquals($net_total, $brokerage_note->getNetTotal());
        $this->assertEquals($result, $brokerage_note->getResult());
    }

    public function testAddBrokerageNote_ShouldCalculateBaisIrCorretly()
    {
        $status_code_expected = 201;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_brokerage_note['total_moviments'] = 10.0;
        $new_brokerage_note['operational_fee'] = 1.0;
        $new_brokerage_note['registration_fee'] = 1.0;
        $new_brokerage_note['emolument_fee'] = 1.0;
        $new_brokerage_note['iss_pis_cofins'] = 1.0;
        $new_brokerage_note['note_irrf_tax'] = 1.0;

        $request_body = json_encode($new_brokerage_note);

        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($brokerage_note->getResult(), $brokerage_note->getCalculationBasisIr());
    }
}