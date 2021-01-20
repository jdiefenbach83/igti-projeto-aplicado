<?php

namespace App\Tests\Functional;

use App\Entity\Broker;
use App\Entity\BrokerageNote;

class BrokerageNoteControllerTest extends BaseTest
{
    public function testAddBrokerageNote_ShouldReturnSuccess()
    {
        $status_code_expected = 201;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $new_brokerage_note = [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => $this->faker->randomFloat(4, 1, 100_000),
            'operational_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'registration_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'emolument_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'iss_pis_cofins' => $this->faker->randomFloat(4, 1, 100_000),
            'note_irrf_tax' => $this->faker->randomFloat(4, 1, 100_000),
        ];

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
    }

    public function testAddBrokerageNote_ShouldCalculateCorretly()
    {
        $status_code_expected = 201;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $new_brokerage_note = [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => $this->faker->randomFloat(4, 1, 100_000),
            'operational_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'registration_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'emolument_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'iss_pis_cofins' => $this->faker->randomFloat(4, 1, 100_000),
            'note_irrf_tax' => $this->faker->randomFloat(4, 1, 100_000),
        ];

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

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $new_brokerage_note = [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => 10.0,
            'operational_fee' => 1.0,
            'registration_fee' => 1.0,
            'emolument_fee' => 1.0,
            'iss_pis_cofins' => 1.0,
            'note_irrf_tax' => 1.0,
        ];

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

    public function getInvalidValuesToCreateBrokerageNote(): iterable {
        yield 'broker_id' => [ 'broker_id', null ];
        yield 'date' => [ 'date', null ];
        yield 'number' => [ 'number', null ];
    }

    /**
     * @dataProvider getInvalidValuesToCreateBrokerageNote
     * @param string $key
     * @param $value
     */
    public function testAddBrokerageNote_ShouldFailWhenCreate(string $key, $value): void
    {
        $status_code_expected = 400;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $new_brokerage_note = [
            'broker_id' => $broker->getId(),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'number' => $this->faker->numberBetween(1, 100_000),
            'total_moviments' => $this->faker->randomFloat(4, 1, 100_000),
            'operational_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'registration_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'emolument_fee' => $this->faker->randomFloat(4, 1, 100_000),
            'iss_pis_cofins' => $this->faker->randomFloat(4, 1, 100_000),
            'note_irrf_tax' => $this->faker->randomFloat(4, 1, 100_000),
        ];

        $new_brokerage_note[$key] = $value;

        $request_body = json_encode($new_brokerage_note);

        $this->client->request('POST', '/api/brokerageNotes', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
    }
}