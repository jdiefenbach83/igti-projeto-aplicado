<?php

namespace App\Tests\Functional;

use App\Entity\Broker;

class BrokerControllerTest extends BaseTest
{
    public function testGetAllBrokers(): void
    {
        $expected_status_code = 200;

        $response = $this->executeRequestWithToken('GET', '/api/brokers');

        self::assertEquals($expected_status_code, $response->getStatusCode());
        self::assertNotEmpty($response->getContent());
    }

    public function testGetBrokerById_ShouldReturnSucess(): void
    {
        $status_code_expected = 200;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $response = $this->executeRequestWithToken('GET', "/api/brokers/{$broker->getId()}");
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertEquals($response_body['content']['id'], $broker->getId());
    }

    public function testGetBrokerById_ShouldReturnNoContent(): void
    {
        $status_code_expected = 204;

        $response = $this->executeRequestWithToken('GET', "/api/brokers/-1");
        $response_body = json_decode($response->getContent(), true);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEmpty($response_body);
    }

    public function testAddBroker_ShouldReturnSuccess(): void
    {
        $status_code_expected = 201;
        $new_broker = [
            'code' => $this->faker->numberBetween(100000, 200000),
            'name' => $this->faker->name(),
            'cnpj' => $this->faker->numberBetween(1, 99999999999999),
            'site' => $this->faker->url(),
        ];

        $request_body = json_encode($new_broker, JSON_THROW_ON_ERROR);
        $response = $this->executeRequestWithToken('POST', '/api/brokers', [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertNotNull($broker);
    }

    public function getInvalidValuesToFail(): array
    {
        return [
            'Code - Empty' => ['code', '',  'This value should not be blank.'],
            'Code - Invalid length' => ['code', '12345678901234567890',  'This value is too long. It should have 10 characters or less.'],
            'Name - Empty' => ['name', '',  'This value should not be blank.'],
            'Name - Invalid length' => ['name', $this->faker->text(500),  'This value is too long. It should have 255 characters or less.'],
            'CNPJ - Empty' => ['cnpj', '',  'This value should not be blank.'],
            'CNPJ - Invalid length' => ['cnpj', $this->faker->numerify('####################'),  'This value is too long. It should have 18 characters or less.'],
            'URL - Invalid url' => ['site', $this->faker->text(50),  'This value is not a valid URL.'],
            'URL - Invalid length' => ['site', $this->faker->text(500),  'This value is too long. It should have 255 characters or less.'],
        ];
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param string $expected_message
     * @throws \JsonException
     */
    public function testAddBroker_ShouldReturnBadRequest(string $key, string $value, string $expected_message): void
    {
        $status_code_expected = 400;
        $new_broker = [
            'code' => $this->faker->numberBetween(1_000_000_000, 2_000_000_000),
            'name' => $this->faker->name(),
            'cnpj' => $this->faker->numerify('##################'),
            'site' => $this->faker->url(),
        ];

        $new_broker[$key] = $value;

        $request_body = json_encode($new_broker, JSON_THROW_ON_ERROR);
        $response = $this->executeRequestWithToken('POST', '/api/brokers', [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testUpdateBroker_ShouldReturnSuccess(): void
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
        $response = $this->executeRequestWithToken('PUT', "/api/brokers/{$broker_to_update->getId()}", [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $updated_broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertEquals($updated_broker->getCode(), $response_body['content']['code']);
        self::assertEquals($updated_broker->getName(), $response_body['content']['name']);
        self::assertEquals($updated_broker->getCnpj(), $response_body['content']['cnpj']);
        self::assertEquals($updated_broker->getSite(), $response_body['content']['site']);
    }

    public function testUpdateBroker_ShouldReturnNotFound(): void
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $response = $this->executeRequestWithToken('PUT', "/api/brokers/{$id}");
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param $expected_message
     */
    public function testUpdateBroker_ShouldReturnBadRequest(string $key, string $value, string $expected_message): void
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
        $response = $this->executeRequestWithToken('PUT', "/api/brokers/{$broker_to_update->getId()}", [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testRemoveBroker_ShouldReturnSuccess(): void
    {
        $status_code_expected = 204;

        $broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy([]);

        $response = $this->executeRequestWithToken('DELETE', "/api/brokers/{$broker->getId()}");

        $removed_broker = $this->entityManager
            ->getRepository(Broker::class)
            ->findOneBy(['id' => $broker->getId()]);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNull($removed_broker);
    }

    public function testRemoveBroker_ShouldReturnNotFound(): void
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $response = $this->executeRequestWithToken('DELETE', "/api/brokers/$id");
        $response_body = json_decode($response->getContent(), true, 512);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }
}