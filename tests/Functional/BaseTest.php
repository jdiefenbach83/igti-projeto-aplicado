<?php

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    protected KernelBrowser $client;
    protected Generator $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['CONTENT_TYPE' => 'application/json']);
        $kernel = $this->client->getKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function executeRequest(string $method, string $uri, array $headers = [], string $content = null): Response
    {
        $this->client->request($method, $uri, [], [], $headers, $content);

        return $this->client->getResponse();
    }

    protected function getApiToken(): string
    {
        $payload = json_encode([
            'email' => 'admin@mail.co',
            'password' => '123456'
        ], JSON_THROW_ON_ERROR);

        $response = $this->executeRequest('POST', '/api/login', [], $payload);

        $responseBody = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('content', $responseBody);
        self::assertArrayHasKey('access_token', $responseBody['content']);

        return $responseBody['content']['access_token'];
    }

    protected function executeRequestWithToken(string $method, string $uri, array $headers = [], string $content = null): Response
    {
        $token = $this->getApiToken();

        $headers['HTTP_Authorization'] = "Bearer $token";

        return $this->executeRequest($method, $uri, $headers, $content);
    }
}
