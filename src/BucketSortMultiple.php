<?php

namespace LifetimeBucketSort;

/**
 * Class BucketSortMultiple
 *
 * To improve performance, once a single date's bucket is calculated, it will be cached for quick access
 *
 * @package LifetimeBucketSort
 */
class BucketSortMultiple
{
    /**
     * @const string
     */
    public const DEFAULT_DATE_COLUMN = 'date_created';

    /**
     * A map of cached dates => bucket pairs
     * @var array
     */
    private $dateBucketCache = [];

    /**
     * @var BucketSort
     */
    private $bucketSort;

    /**
     * @var OffsetCalculator
     */
    private $offsetCalculator;

    /**
     * @var string
     */
    private $columnName;

    /**
     * BucketSortMultiple constructor.
     *
     * @param BucketSort $bucketSort
     * @param OffsetCalculator $offsetCalculator
     */
    public function __construct(BucketSort $bucketSort, OffsetCalculator $offsetCalculator)
    {
        $this->bucketSort = $bucketSort;
        $this->offsetCalculator = $offsetCalculator;
        $this->columnName = self::DEFAULT_DATE_COLUMN;
    }

    /**
     * @param string $columnName
     */
    public function setDateColumnName(string $columnName)
    {
        $this->columnName = $columnName;
    }

    /**
     * Calculate the bucket on a batch of records
     *
     * @param array $batchRecords
     * @return \Generator
     * @throws BucketSortException
     */
    public function batchCalculate(array $batchRecords)
    {
        foreach ($batchRecords as $eachRecord) {
            yield $this->calculateSingle($eachRecord);
        }
        return true;
    }

    /**
     * Calculate a single record bucket
     *
     * @param array $record
     * @return int
     * @throws BucketSortException
     */
    public function calculateSingle(array $record): int
    {
        if (!isset($record[$this->columnName])) {
            throw new BucketSortException(
                BucketSortException::SECONDARY_DATE_MISSING_TITLE,
                BucketSortException::SECONDARY_DATE_MISSING_CODE
            );
        }

        /**
         * Return the bucket directly from the cache, if available
         */
        if (isset($this->dateBucketCache[$record[$this->columnName]])) {
            return $this->dateBucketCache[$record[$this->columnName]];
        }

        $diffDays = $this->offsetCalculator->calculateOffsetInDays($record[$this->columnName]);
        $bucketId = $this->bucketSort->getBucketId($diffDays);

        /**
         * Save the date / bucket pair in cache for any following requests with the same date
         */
        $this->dateBucketCache[$record[$this->columnName]] = $bucketId;
        return $bucketId;
    }

    /**
     * Return the generated DateBucketMap array
     *
     * @return array
     */
    public function getDateBucketMap()
    {
        return $this->dateBucketCache;
    }
}