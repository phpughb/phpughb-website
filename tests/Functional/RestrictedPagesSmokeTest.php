<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class RestrictedPagesSmokeTest extends WebTestCase
{
    /**
     * @test
     * @dataProvider provideRestrictedPages
     */
    public function restrictedPagesRedirectedToLogin(string $path): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, $path);

        self::assertResponseRedirects('/admin/login');
    }

    public function provideRestrictedPages(): iterable
    {
        yield 'Admin Dashboard' => ['/admin/dashboard'];
    }
}
