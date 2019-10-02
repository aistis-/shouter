<?php


namespace App\Quote\DataSource;

interface DataSourceInterface
{
    /**
     * @param string $personSlug
     * @param int $limit
     *
     * @return array|string[]
     */
    public function find(string $personSlug, int $limit): array;
}
