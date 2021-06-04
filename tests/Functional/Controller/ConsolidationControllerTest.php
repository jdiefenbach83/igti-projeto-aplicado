<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\BaseTest;

class ConsolidationControllerTest extends BaseTest
{
    public function testConsolidation_shouldGetAllConsolidations(): void
    {
        $expected_status_code = 200;

        $response = $this->executeRequestWithToken('GET', '/api/consolidations');

        self::assertEquals($expected_status_code, $response->getStatusCode());
        self::assertNotEmpty($response->getContent());
    }
}