<?php

namespace App\Tests\Controller;

use App\Tests\Functional\BaseTest;

class GoodControllerTest extends BaseTest
{
    public function testGood_shouldGetAllGoods(): void
    {
        $this->client->request('GET', '/api/goods');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertNotEmpty($this->client->getResponse()->getContent());
    }
}