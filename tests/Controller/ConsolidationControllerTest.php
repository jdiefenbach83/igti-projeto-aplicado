<?php

namespace App\Tests\Controller;

use App\Tests\Functional\BaseTest;

class ConsolidationControllerTest extends BaseTest
{
    public function testConsolidation_shouldGetAllConsolidations(): void
    {
        $this->client->request('GET', '/api/consolidations');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertNotEmpty($this->client->getResponse()->getContent());
    }
}