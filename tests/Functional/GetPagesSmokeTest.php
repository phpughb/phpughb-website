<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class GetPagesSmokeTest extends WebTestCase
{
    /**
     * @test
     * @dataProvider provideGetRoutes
     */
    public function getPagesAreSuccessful(string $path): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, $path);
        self::assertResponseIsSuccessful();
    }

    public function provideGetRoutes(): iterable
    {
        yield 'Home' => ['/'];
        yield 'Login' => ['/admin/login'];
    }
}
