<?php

namespace App\Tests\Unit\Shout;

use App\Shout\Shouter;
use PHPUnit\Framework\TestCase;

class ShouterUnitTest extends TestCase
{
    /**
     * @param array $input
     * @param array $expected
     *
     * @dataProvider getData
     */
    public function test(array $input, array $expected): void
    {
        self::assertSame($expected, Shouter::shoutAll($input));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            [
                'input' => [],
                'expected' => [],
            ],
            [
                'input' => ['someone', 'shouting.', 'On me!', 'or FOR ME?'],
                'expected' => ['SOMEONE!', 'SHOUTING!', 'ON ME!', 'OR FOR ME!'],
            ],
        ];
    }
}
