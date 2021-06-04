<?php

namespace App\Tests\Functional;

use App\Entity\Company;

class CompanyControllerTest extends BaseTest
{
    public function testGetAllCompanies(): void
    {
        $expected_status_code = 200;

        $response = $this->executeRequestWithToken('GET', '/api/companies');
        
        self::assertEquals($expected_status_code, $response->getStatusCode());
        self::assertNotEmpty($response->getContent());
    }

    public function testGetCompanyById_ShouldReturnSucess(): void
    {
        $status_code_expected = 200;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $response = $this->executeRequestWithToken('GET', "/api/companies/{$company->getId()}");
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertEquals($response_body['content']['id'], $company->getId());
    }

    public function testGetCompanyById_ShouldReturnNoContent(): void
    {
        $status_code_expected = 204;

        $response = $this->executeRequestWithToken('GET', '/api/companies/-1');
        $response_body = json_decode($response->getContent(), true);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEmpty($response_body);
    }

    public function testAddCompany_ShouldReturnSuccess(): void
    {
        $status_code_expected = 201;
        $new_company = [
            'code' => $this->faker->numberBetween(100000, 200000),
            'name' => $this->faker->name(),
            'cnpj' => $this->faker->numberBetween(1, 99999999999999),
            'site' => $this->faker->url(),
        ];

        $request_body = json_encode($new_company, JSON_THROW_ON_ERROR);
        $response = $this->executeRequestWithToken('POST', '/api/companies', [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertNotNull($company);
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
    public function testAddCompany_ShouldReturnBadRequest(string $key, string $value, string $expected_message): void
    {
        $status_code_expected = 400;
        $new_company = [
            'cnpj' => $this->faker->numberBetween(99_999_999_000_000, 99_999_999_999_999),
            'name' => $this->faker->name(),
        ];

        $new_company[$key] = $value;

        $request_body = json_encode($new_company, JSON_THROW_ON_ERROR);
        $response = $this->executeRequestWithToken('POST', '/api/companies', [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testUpdateCompany_ShouldReturnSuccess(): void
    {
        $status_code_expected = 200;

        $company_to_update = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $company_to_update->setName($this->faker->name());
        $company_to_update->setCnpj($this->faker->numberBetween(1, 99999999999999));

        $request_body = json_encode($company_to_update);
        $response = $this->executeRequestWithToken('PUT', "/api/companies/{$company_to_update->getId()}", [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $updated_company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $response_body['content']['id']]);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNotEmpty($response_body);
        self::assertEquals($updated_company->getCnpj(), $response_body['content']['cnpj']);
        self::assertEquals($updated_company->getName(), $response_body['content']['name']);
    }

    public function testUpdateCompany_ShouldReturnNotFound(): void
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);

        $response = $this->executeRequestWithToken('PUT', "/api/companies/{$id}");
        $response_body = json_decode($response->getContent(), true);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }

    /**
     * @dataProvider getInvalidValuesToFail
     * @param string $key
     * @param string $value
     * @param $expected_message
     */
    public function testUpdateCompany_ShouldReturnBadRequest(string $key, string $value, string $expected_message): void
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
        $request_body = json_encode($modified_body, JSON_THROW_ON_ERROR);

        $response = $this->executeRequestWithToken('PUT', "/api/companies/{$company_to_update->getId()}", [], $request_body);
        $response_body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertEquals($expected_message, $response_body['content']['validation_errors'][0]['message']);
    }

    public function testRemoveCompany_ShouldReturnSuccess(): void
    {
        $status_code_expected = 204;

        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy([]);

        $response = $this->executeRequestWithToken('DELETE', "/api/companies/{$company->getId()}");

        $removed_company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $company->getId()]);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertNull($removed_company);
    }

    public function testRemoveCompany_ShouldReturnNotFound(): void
    {
        $status_code_expected = 404;

        $id = $this->faker->numberBetween(1000000, 2000000);
        $response = $this->executeRequestWithToken('DELETE', "/api/companies/$id");
        $response_body = json_decode($response->getContent(), true);

        self::assertEquals($status_code_expected, $response->getStatusCode());
        self::assertArrayNotHasKey('content', $response_body);
    }
}