<?php

namespace App\Tests\Trait;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait AssertTrait
{
    public static function assertJsonErrorResponse(KernelBrowser $client, array $errors): void
    {
        $response = $client->getResponse();

        self::assertEquals(400, $response->getStatusCode());

        $expectedJson = ['errors' => $errors];

        self::assertJsonStringEqualsJsonString(json_encode($expectedJson), $response->getContent());
    }
}
