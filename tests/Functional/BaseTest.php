<?php

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
        $this->client = static::createClient();
        $kernel = $this->client->getKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}