<?php

namespace App\Quote;

use App\Quote\DataSource\DataSourceInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class QuoteProvider
{
    /** @var DataSourceInterface */
    private $dataSource;

    /** @var AdapterInterface */
    private $cache;

    /**
     * @param DataSourceInterface $dataSource
     * @param AdapterInterface $cache
     */
    public function __construct(DataSourceInterface $dataSource, AdapterInterface $cache)
    {
        $this->dataSource = $dataSource;
        $this->cache = $cache;
    }

    /**
     * @param string $personSlug
     * @param int $limit
     *
     * @return array|string[]
     */
    public function get(string $personSlug, int $limit): array
    {
        $cachedItem = $this->cache->getItem($personSlug);

        if ($cachedItem->isHit() && $cachedItem->get()['limit'] >= $limit) {
            // Cache is hit and limit was satisfiable by cache.
            return array_slice($cachedItem->get()['quotes'], 0, $limit);
        }

        $quotes = $this->dataSource->find($personSlug, $limit);

        // Cache the result.
        $this->cache->save($cachedItem->set(['quotes' => $quotes, 'limit' => $limit]));

        return array_slice($quotes, 0, $limit);
    }
}
