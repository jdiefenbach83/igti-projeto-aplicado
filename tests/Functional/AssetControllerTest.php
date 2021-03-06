<?php

namespace App\Tests\Functional;

use App\Entity\Asset;
use App\Entity\Company;

class AssetControllerTest extends BaseTest
{
    public function testGetAllAssets()
    {
        $this->client->request('GET', '/api/assets');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotEmpty($this->client->getResponse()->getContent());
    }

    public function testGetAssetById_ShouldReturnSucess()
    {
        $status_code_expected = 200;

        $asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([]);

        $this->client->request('GET', "/api/assets/{$asset->getId()}");
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($response_body['content']['id'], $asset->getId());
    }

    public function testGetAssetById_ShouldReturnNoContent()
    {
        $status_code_expected = 204;

        $this->client->request('GET', '/api/assets/-1');
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEmpty($response_body);
    }

    public function testAddAsset_ShouldReturnSuccess()
    {
        $status_code_expected = 201;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $new_asset = [
            'code' => $this->faker->text(10),
            'type' => $this->faker->randomElement(Asset::getTypes()),
            'company_id' => $company->getId(),
        ];

        $request_body = json_encode($new_asset);

        $this->client->request('POST', '/api/assets', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertNotNull($asset);
    }

    public function getInvalidValuesToFail(): array
    {
        return [
            'Code - Empty' => ['code', '',  'This value should not be blank.'],
            'Code - Invalid length' => ['code', 'ABCDEF123456',  'This value is too long. It should have 10 characters or less.'],
            'Type - Empty' => ['type', '',  'This value should not be blank.'],
            'Type - Invalid length' => ['type', 'ABCDEF', 'The value you selected is not a valid choice.'],
            'Company Id - Empty' => ['company_id', '',  'This value should not be blank.'],
        ];
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param $expected_message
     */
    public function testAddAsset_ShouldReturnBadRequest(string $key, string $value, string $expected_message)
    {
        $status_code_expected = 400;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $new_asset = [
            'code' => $this->faker->text(10),
            'type' => $this->faker->randomElement(Asset::getTypes()),
            'company_id' => $company->getId(),
        ];

        $new_asset[$key] = $value;

        $request_body = json_encode($new_asset);

        $this->client->request('POST', '/api/assets', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testUpdateAsset_ShouldReturnSuccess()
    {
        $status_code_expected = 200;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $asset_to_update = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([]);

        $asset_to_update->setCode($this->faker->text(10));
        $asset_to_update->setType($this->faker->randomElement(Asset::getTypes()));
        $asset_to_update->setCompany($company);

        $request_body = json_encode($asset_to_update);

        $this->client->request('PUT', "/api/assets/{$asset_to_update->getId()}", [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $updated_asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($updated_asset->getCode(), $response_body['content']['code']);
        $this->assertEquals($updated_asset->getType(), $response_body['content']['type']);
        $this->assertEquals($updated_asset->getCompany()->getId(), $response_body['content']['company_id']);
    }

    public function testUpdateAsset_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('PUT', "/api/assets/{$id}");

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
    public function testUpdateAsset_ShouldReturnBadRequest(string $key, string $value, string $expected_message)
    {
        $status_code_expected = 400;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $asset_to_update = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([]);

        $asset_to_update->setCode($this->faker->text(10));
        $asset_to_update->setType($this->faker->randomElement(Asset::getTypes()));
        $asset_to_update->setCompany($company);

        $request_body = json_encode($asset_to_update);
        $modified_body = json_decode($request_body, true);
        $modified_body[$key] = $value;
        $request_body = json_encode($modified_body);

        $this->client->request('PUT', "/api/assets/{$asset_to_update->getId()}", [], [], [], $request_body);
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testRemoveAsset_ShouldReturnSuccess()
    {
        $status_code_expected = 204;

        $asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy([]);

        $this->client->request('DELETE', "/api/assets/{$asset->getId()}");
        $response = $this->client->getResponse();

        $removed_asset = $this->entityManager
            ->getRepository(Asset::class)
            ->findOneBy(['id' => $asset->getId()]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNull($removed_asset);
    }

    public function testRemoveAsset_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('DELETE', "/api/assets/{$id}");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }
}