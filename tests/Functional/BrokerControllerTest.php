<?php

namespace App\Tests\Functional;

use App\Entity\Broker;

class BrokerControllerTest extends BaseTest
{
    public function testGetAllBrokers()
    {
        $this->client->request('GET', '/api/brokers');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotEmpty($this->client->getResponse()->getContent());
    }

    public function testGetBrokerById_ShouldReturnSucess()
    {
        $status_code_expected = 200;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $this->client->request('GET', "/api/brokers/{$broker->getId()}");
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($response_body['content']['id'], $broker->getId());
    }

    public function testGetBrokerById_ShouldReturnNoContent()
    {
        $status_code_expected = 204;

        $this->client->request('GET', '/api/brokers/-1');
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEmpty($response_body);
    }

    public function testAddBroker_ShouldReturnSuccess()
    {
        $status_code_expected = 201;
        $new_broker = [
            'code' => $this->faker->numberBetween(100000, 200000),
            'name' => $this->faker->name(),
            'cnpj' => $this->faker->numberBetween(1, 99999999999999),
            'site' => $this->faker->url(),
        ];

        $request_body = json_encode($new_broker);

        $this->client->request('POST', '/api/brokers', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertNotNull($broker);
    }

    public function getInvalidValuesToFail(): array
    {
        return [
            'Code - Empty' => ['code', '',  'This value should not be blank.'],
            'Code - Invalid length' => ['code', '12345678901234567890',  'This value is too long. It should have 10 characters or less.'],
            'Name - Empty' => ['name', '',  'This value should not be blank.'],
            'Name - Invalid length' => ['name', $this->faker->text(500),  'This value is too long. It should have 255 characters or less.'],
            'CNPJ - Empty' => ['cnpj', '',  'This value should not be blank.'],
            'CNPJ - Invalid length' => ['cnpj', $this->faker->numberBetween(999_999_999_000_000, 999_999_999_999_999),  'This value is too long. It should have 14 characters or less.'],
            'URL - Invalid url' => ['site', $this->faker->text(50),  'This value is not a valid URL.'],
            'URL - Invalid length' => ['site', $this->faker->text(500),  'This value is too long. It should have 255 characters or less.'],
        ];
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param $expected_message
     */
    public function testAddBroker_ShouldReturnBadRequest(string $key, string $value, string $expected_message)
    {
        $status_code_expected = 400;
        $new_broker = [
            'code' => $this->faker->numberBetween(1_000_000_000, 2_000_000_000),
            'name' => $this->faker->name(),
            'cnpj' => $this->faker->numberBetween(99_999_999_000_000, 99_999_999_999_999),
            'site' => $this->faker->url(),
        ];

        $new_broker[$key] = $value;

        $request_body = json_encode($new_broker);

        $this->client->request('POST', '/api/brokers', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testUpdateBroker_ShouldReturnSuccess()
    {
        $status_code_expected = 200;

        $broker_to_update = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $broker_to_update->setCode($this->faker->numberBetween(100000, 200000));
        $broker_to_update->setName($this->faker->name());
        $broker_to_update->setCnpj($this->faker->numberBetween(1, 99999999999999));
        $broker_to_update->setSite($this->faker->url());

        $request_body = json_encode($broker_to_update);

        $this->client->request('PUT', "/api/brokers/{$broker_to_update->getId()}", [], [], [], $request_body);

        $response = $this->client->getResponse();
        print_r($response);
        $response_body = json_decode($response->getContent(), true);

        $updated_broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($updated_broker->getCode(), $response_body['content']['code']);
        $this->assertEquals($updated_broker->getName(), $response_body['content']['name']);
        $this->assertEquals($updated_broker->getCnpj(), $response_body['content']['cnpj']);
        $this->assertEquals($updated_broker->getSite(), $response_body['content']['site']);
    }

    public function testUpdateBroker_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('PUT', "/api/brokers/{$id}");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param $expected_message
     */
    public function testUpdateBroker_ShouldReturnBadRequest(string $key, string $value, string $expected_message)
    {
        $status_code_expected = 400;

        $broker_to_update = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $broker_to_update->setCode($this->faker->numberBetween(100000, 200000));
        $broker_to_update->setName($this->faker->name());
        $broker_to_update->setCnpj($this->faker->numberBetween(1, 99999999999999));
        $broker_to_update->setSite($this->faker->url());

        $request_body = json_encode($broker_to_update);
        $modified_body = json_decode($request_body, true);
        $modified_body[$key] = $value;
        $request_body = json_encode($modified_body);

        $this->client->request('PUT', "/api/brokers/{$broker_to_update->getId()}", [], [], [], $request_body);
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testRemoveBroker_ShouldReturnSuccess()
    {
        $status_code_expected = 204;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $this->client->request('DELETE', "/api/brokers/{$broker->getId()}");
        $response = $this->client->getResponse();

        $removed_broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy(['id' => $broker->getId()]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNull($removed_broker);
    }

    public function testRemoveBroker_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('DELETE', "/api/brokers/{$id}");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }
}