<?php

namespace App\Tests\Functional;

use App\Entity\Broker;
use App\Entity\BrokerageNote;

class BrokerageNoteControllerTest extends BaseTest
{
    public function testAddBroker_ShouldReturnSuccess()
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
}