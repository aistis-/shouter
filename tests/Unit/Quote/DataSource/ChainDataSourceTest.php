<?php

namespace App\Tests\Unit\Quote\DataSource;

use App\Quote\DataSource\ChainDataSource;
use App\Quote\DataSource\DataSourceInterface;
use PHPUnit\Framework\TestCase;

class ChainDataSourceTest extends TestCase
{
    /**
     * @param array $sourceConfigs
     * @param array $expected
     *
     * @dataProvider getData
     *
     * Test results chaining and adding up as final result.
     */
    public function test(array $sourceConfigs, array $expected): void
    {
        $sources = array_map(
            function (array $config) {
                return $this->createDataSource($config);
            },
            $sourceConfigs
        );

        $dataSource = new ChainDataSource($sources);

        self::assertSame($expected, $dataSource->find('author', 5));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            [
                'sources' => [
                    ['expected_limit' => 5, 'result' => ['1', '2', '3']],
                ],
                'expected' => ['1', '2', '3'],
            ],
            [
                'sources' => [
                    ['expected_limit' => 5, 'result' => ['1', '2', '3']],
                    ['expected_limit' => 2, 'result' => ['4', '5']],
                    [],
                ],
                'expected' => ['1', '2', '3', '4', '5'],
            ],
            [
                'sources' => [
                    ['expected_limit' => 5, 'result' => ['1', '2']],
                    ['expected_limit' => 3, 'result' => ['3']],
                    ['expected_limit' => 2, 'result' => ['4']],
                    ['expected_limit' => 1, 'result' => ['5']],
                    [],
                    [],
                ],
                'expected' => ['1', '2', '3', '4', '5'],
            ],
        ];
    }

    /**
     * @param array $config
     *
     * @return DataSourceInterface
     */
    private function createDataSource(array $config): DataSourceInterface
    {
        $source = $this->createMock(DataSourceInterface::class);

        if (empty($config)) {
            $source->expects($this->never())->method('find');
        } else {
            $source
                ->expects($this->once())
                ->method('find')
                ->with('author', $config['expected_limit'])
                ->willReturn($config['result']);
        }

        return $source;
    }
}
