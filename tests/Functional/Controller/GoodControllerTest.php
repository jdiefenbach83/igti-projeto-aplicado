<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\BaseTest;

class GoodControllerTest extends BaseTest
{
    public function testGood_shouldGetAllGoods(): void
    {
        $expected_status_code = 200;

        $response = $this->executeRequestWithToken('GET', '/api/goods');

        self::assertEquals($expected_status_code, $response->getStatusCode());
        self::assertNotEmpty($response->getContent());
    }
}