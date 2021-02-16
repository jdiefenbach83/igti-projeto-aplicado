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

        $asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([]);

        return [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => $this->faker->randomFloat(4, 1, 100_000),
            'operational_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'registration_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'emolument_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'iss_pis_cofins' => $this->faker->randomFloat(4, 1, 100_000),
            'note_irrf_tax' => $this->faker->randomFloat(4, 1, 100_000),
            'operations' => [
                [
                    'type' => Operation::TYPE_BUY,
                    'asset_id' => $asset->getId(),
                    'quantity' => $this->faker->numberBetween(1, 99),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                ],
                [
                    'type' => Operation::TYPE_BUY,
                    'asset_id' => $asset->getId(),
                    'quantity' => $this->faker->numberBetween(1, 99),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                ],
            ]
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

        $brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

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
        $this->assertNotNull($new_brokerage_note['operations']);
        $this->assertEquals($new_brokerage_note['operations'][0]['type'], $brokerage_note->getOperations()[0]->getType());
        $this->assertEquals($new_brokerage_note['operations'][0]['asset_id'], $brokerage_note->getOperations()[0]->getAsset()->getId());
        $this->assertEquals($new_brokerage_note['operations'][0]['quantity'], $brokerage_note->getOperations()[0]->getQuantity());
        $this->assertEquals($new_brokerage_note['operations'][0]['price'], $brokerage_note->getOperations()[0]->getPrice());
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

    public function getInvalidValuesToCreateOrUpdateBrokerageNoteWithOperations(): iterable {
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
     * @dataProvider getInvalidValuesToCreateOrUpdateBrokerageNoteWithOperations
     * @param string $key
     * @param $value
     */
    public function testAddBrokerageNote_ShouldFailWhenCreateWithOperation(string $key, $value): void
    {
        $status_code_expected = 400;

        $new_brokerage_note = $this->createBrokerageNote();
        $new_brokerage_note['operations'][0][$key] = $value;

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

        $asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([], ['id' => 'DESC']);

        $brokerage_note_to_update_entity = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->find($new_response_body['content']['id']);

        $brokerage_note_to_update['broker_id'] = $brokerage_note_to_update_entity->getBroker()->getId();
        $brokerage_note_to_update['date'] = $this->faker->dateTime()->format('Y-m-d');
        $brokerage_note_to_update['number'] = $this->faker->numberBetween(1, 100_000);
        $brokerage_note_to_update['total_moviments'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['operational_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['registration_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['emolument_fee'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['iss_pis_cofins'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['note_irrf_tax'] = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage_note_to_update['operations'][0]['type'] = Operation::TYPE_SELL;
        $brokerage_note_to_update['operations'][0]['asset_id'] = $asset->getId();
        $brokerage_note_to_update['operations'][0]['quantity'] = $this->faker->numberBetween(1, 99);
        $brokerage_note_to_update['operations'][0]['price'] = $this->faker->randomFloat(2, 1, 100);
        $brokerage_note_to_update['operations'][1]['type'] = Operation::TYPE_SELL;
        $brokerage_note_to_update['operations'][1]['asset_id'] = $asset->getId();
        $brokerage_note_to_update['operations'][1]['quantity'] = $this->faker->numberBetween(1, 99);
        $brokerage_note_to_update['operations'][1]['price'] = $this->faker->randomFloat(2, 1, 100);

        $request_body = json_encode($brokerage_note_to_update);

        $this->client->request('PUT', "/api/brokerageNotes/{$brokerage_note_to_update_entity->getId()}", [], [], [], $request_body);

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

        $this->assertEquals($updated_brokerage_note->getOperations()[0]->getType(), $update_response_body['content']['operations'][0]['type']);
        $this->assertEquals($updated_brokerage_note->getOperations()[0]->getAsset()->getId(), $update_response_body['content']['operations'][0]['asset_id']);
        $this->assertEquals($updated_brokerage_note->getOperations()[0]->getQuantity(), $update_response_body['content']['operations'][0]['quantity']);
        $this->assertEquals($updated_brokerage_note->getOperations()[0]->getPrice(), $update_response_body['content']['operations'][0]['price']);

        $this->assertEquals($updated_brokerage_note->getOperations()[1]->getType(), $update_response_body['content']['operations'][1]['type']);
        $this->assertEquals($updated_brokerage_note->getOperations()[1]->getAsset()->getId(), $update_response_body['content']['operations'][1]['asset_id']);
        $this->assertEquals($updated_brokerage_note->getOperations()[1]->getQuantity(), $update_response_body['content']['operations'][1]['quantity']);
        $this->assertEquals($updated_brokerage_note->getOperations()[1]->getPrice(), $update_response_body['content']['operations'][1]['price']);
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

        $this->client->request('DELETE', "/api/brokerageNotes/{$new_response_body['content']['id']}");
        $remove_response = $this->client->getResponse();

        $removed_brokerage_note = $this->entityManager
            ->getRepository(BrokerageNote::class)
            ->findOneBy(['id' => $new_response_body['content']['id']]);

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
}