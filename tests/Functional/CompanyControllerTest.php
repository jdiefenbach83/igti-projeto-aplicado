<?php

namespace App\Tests\Functional;

use App\Entity\Company;

class CompanyControllerTest extends BaseTest
{
    public function testGetAllCompanies()
    {
        $this->client->request('GET', '/api/companies');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotEmpty($this->client->getResponse()->getContent());
    }

    public function testGetCompanyById_ShouldReturnSucess()
    {
        $status_code_expected = 200;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $this->client->request('GET', "/api/companies/{$company->getId()}");
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($response_body['content']['id'], $company->getId());
    }

    public function testGetCompanyById_ShouldReturnNoContent()
    {
        $status_code_expected = 204;

        $this->client->request('GET', '/api/companies/-1');
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEmpty($response_body);
    }

    public function testAddCompany_ShouldReturnSuccess()
    {
        $status_code_expected = 201;
        $new_company = [
            'code' => $this->faker->numberBetween(100000, 200000),
            'name' => $this->faker->name(),
            'cnpj' => $this->faker->numberBetween(1, 99999999999999),
            'site' => $this->faker->url(),
        ];

        $request_body = json_encode($new_company);

        $this->client->request('POST', '/api/companies', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertNotNull($company);
    }

    public function getInvalidValuesToFail(): array
    {
        return [
            'Name - Empty' => ['name', '',  'This value should not be blank.'],
            'Name - Invalid length' => ['name', $this->faker->text(500),  'This value is too long. It should have 255 characters or less.'],
            'CNPJ - Empty' => ['cnpj', '',  'This value should not be blank.'],
            'CNPJ - Invalid length' => ['cnpj', $this->faker->text(),  'This value is too long. It should have 18 characters or less.'],
        ];
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param $expected_message
     */
    public function testAddCompany_ShouldReturnBadRequest(string $key, string $value, string $expected_message)
    {
        $status_code_expected = 400;
        $new_company = [
            'cnpj' => $this->faker->numberBetween(99_999_999_000_000, 99_999_999_999_999),
            'name' => $this->faker->name(),
        ];

        $new_company[$key] = $value;

        $request_body = json_encode($new_company);

        $this->client->request('POST', '/api/companies', [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testUpdateCompany_ShouldReturnSuccess()
    {
        $status_code_expected = 200;

        $company_to_update = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $company_to_update->setName($this->faker->name());
        $company_to_update->setCnpj($this->faker->numberBetween(1, 99999999999999));

        $request_body = json_encode($company_to_update);

        $this->client->request('PUT', "/api/companies/{$company_to_update->getId()}", [], [], [], $request_body);

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $updated_company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNotEmpty($response_body);
        $this->assertEquals($updated_company->getCnpj(), $response_body['content']['cnpj']);
        $this->assertEquals($updated_company->getName(), $response_body['content']['name']);
    }

    public function testUpdateCompany_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('PUT', "/api/companies/{$id}");

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
    public function testUpdateCompany_ShouldReturnBadRequest(string $key, string $value, string $expected_message)
    {
        $status_code_expected = 400;

        $company_to_update = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $company_to_update->setCnpj($this->faker->numberBetween(1, 99999999999999));
        $company_to_update->setName($this->faker->name());

        $request_body = json_encode($company_to_update);
        $modified_body = json_decode($request_body, true);
        $modified_body[$key] = $value;
        $request_body = json_encode($modified_body);

        $this->client->request('PUT', "/api/companies/{$company_to_update->getId()}", [], [], [], $request_body);
        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testRemoveCompany_ShouldReturnSuccess()
    {
        $status_code_expected = 204;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $this->client->request('DELETE', "/api/companies/{$company->getId()}");
        $response = $this->client->getResponse();

        $removed_company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $company->getId()]);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertNull($removed_company);
    }

    public function testRemoveCompany_ShouldReturnNotFound()
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $this->client->request('DELETE', "/api/companies/{$id}");

        $response = $this->client->getResponse();
        $response_body = json_decode($response->getContent(), true);

        $this->assertEquals($status_code_expected, $response->getStatusCode());
        $this->assertArrayNotHasKey('content', $response_body);
    }
}