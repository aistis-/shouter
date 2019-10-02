<?php

namespace App\Quote\DataSource;

use Cocur\Slugify\SlugifyInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Loads data from JSON data source the very first time when data is requested.
 */
class JsonDataSource implements DataSourceInterface
{
    /** @var SlugifyInterface */
    private $slugifier;

    /** @var string */
    private $filePath;

    /**
     * @var array|null
     *
     * Loaded full data set into the memory. If null - data loading was not initiated yet.
     */
    private $data;

    /**
     * @param SlugifyInterface $slugifier
     * @param string $filePath
     */
    public function __construct(SlugifyInterface $slugifier, string $filePath)
    {
        $this->slugifier = $slugifier;
        $this->filePath = $filePath;
    }

    /**
     * {@inheritDoc}
     */
    public function find(string $personSlug, int $limit): array
    {
        $this->loadData();

        if (isset($this->data[$personSlug])) {
            return array_slice($this->data[$personSlug], 0, $limit);
        }

        return [];
    }

    /**
     * @return array|string[]
     *
     * For the sake of the test
     */
    public function getAvailable(): array
    {
        $this->loadData();

        return array_keys($this->data);
    }

    /**
     * Loads data into the memory.
     */
    private function loadData(): void
    {
        if (null !== $this->data) {
            // Data is already loaded.
            return;
        }

        $json = file_get_contents($this->filePath);
        $data = (new JsonEncoder())->decode($json, JsonEncoder::FORMAT);

        $this->data = [];

        foreach ($data['quotes'] as $quote) {
            $this->data[$this->slugifier->slugify($quote['author'])][] = $quote['quote'];
        }
    }
}
