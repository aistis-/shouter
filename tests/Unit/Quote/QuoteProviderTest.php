<?php

namespace App\Tests\Unit\Quote;

use App\Quote\DataSource\DataSourceInterface;
use App\Quote\QuoteProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class QuoteProviderTest extends TestCase
{
    /**
     * Test cache hitting when limit is same or less than previous calls.
     */
    public function test(): void
    {
        $dataSource = $this->createMock(DataSourceInterface::class);
        $dataSource
            ->expects($this->exactly(2))
            ->method('find')
            ->withConsecutive(
                ['author', 3],
                ['author', 4]
            )
            ->willReturnOnConsecutiveCalls(
                ['0', '1', '2'],
                ['0', '1', '2', '3']
            );

        $provider = new QuoteProvider($dataSource, new ArrayAdapter());

        self::assertSame(['0', '1', '2'], $provider->get('author', 3));
        self::assertSame(['0', '1', '2'], $provider->get('author', 3));
        self::assertSame(['0', '1'], $provider->get('author', 2));
        self::assertSame(['0', '1', '2', '3'], $provider->get('author', 4));
        self::assertSame(['0'], $provider->get('author', 1));
    }
}
