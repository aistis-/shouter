<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testShoutSuccess(): void
    {
        $client = self::createClient();

        $client->request('GET', '/shout/steve-jobs?limit=1');
        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertSame(
            '["YOUR TIME IS LIMITED, SO DON\u2019T WASTE IT LIVING SOMEONE ELSE\u2019S LIFE!"]',
            $client->getResponse()->getContent()
        );

        $client->request('GET', '/shout/steve-jobs?limit=2');
        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertSame(
            '["YOUR TIME IS LIMITED, SO DON\u2019T WASTE IT LIVING SOMEONE ELSE\u2019S LIFE!",'
            . '"THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!"]',
            $client->getResponse()->getContent()
        );

        $client->request('GET', '/shout/steve-jobs?limit=10');
        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertSame(
            '["YOUR TIME IS LIMITED, SO DON\u2019T WASTE IT LIVING SOMEONE ELSE\u2019S LIFE!",'
            . '"THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!"]',
            $client->getResponse()->getContent()
        );

        $client->request('GET', '/shout/steve-jobs-404?limit=3');
        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertSame('[]', $client->getResponse()->getContent());
    }

    public function testShoutInvalid(): void
    {
        $client = self::createClient();

        $client->request('GET', '/shout/steve-jobs');
        self::assertSame(400, $client->getResponse()->getStatusCode());
        self::assertSame(
            '{"error":"GET parameter [limit] is missing"}',
            $client->getResponse()->getContent()
        );

        $client->request('GET', '/shout/steve-jobs?limit=0');
        self::assertSame(400, $client->getResponse()->getStatusCode());
        self::assertSame(
            '{"error":"[limit] must be between 1 and 10"}',
            $client->getResponse()->getContent()
        );

        $client->request('GET', '/shout/steve-jobs?limit=11');
        self::assertSame(400, $client->getResponse()->getStatusCode());
        self::assertSame(
            '{"error":"[limit] must be between 1 and 10"}',
            $client->getResponse()->getContent()
        );
    }
}
