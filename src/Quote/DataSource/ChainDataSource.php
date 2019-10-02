<?php

namespace App\Quote\DataSource;

class ChainDataSource implements DataSourceInterface
{
    /** @var array|DataSourceInterface[] */
    private $dataSources;

    /**
     * @param DataSourceInterface[]|array $dataSources
     */
    public function __construct(array $dataSources)
    {
        $this->dataSources = $dataSources;
    }

    /**
     * {@inheritDoc}
     *
     * Chain multiple data sources by iterating over every of them till the limit of results is reached.
     */
    public function find(string $personSlug, int $limit): array
    {
        $result = [];

        foreach ($this->dataSources as $dataSource) {
            $result = array_merge($result, $dataSource->find($personSlug, $limit - count($result)));

            if (count($result) >= $limit) {
                return array_slice($result, 0, $limit);
            }
        }

        return $result;
    }
}
