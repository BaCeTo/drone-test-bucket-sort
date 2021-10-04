<?php

namespace LifetimeBucketSort;

/**
 * Class BucketSort
 *
 * @package LifetimeBucketSort
 */
class BucketSort
{
    /**
     * @var array
     */
    private $bucketSettings;

    /**
     * BucketSort constructor.
     *
     * @param array $bucketRangeSettings
     */
    public function __construct(array $bucketRangeSettings)
    {
        $this->bucketSettings = $bucketRangeSettings;
    }

    /**
     * Sort the bucket from the days offset
     *
     * @param int $offsetDays
     * @return integer
     * @throws BucketSortException
     */
    public function getBucketId(int $offsetDays): int
    {
        if ($offsetDays < 0) {
            throw new BucketSortException(
                BucketSortException::REFERENCE_DATE_AFTER_SECONDARY_DATE_TITLE,
                BucketSortException::REFERENCE_DATE_AFTER_SECONDARY_DATE_CODE
            );
        }

        $bucketId = 0;
        foreach($this->bucketSettings as $rangeId => $limit) {
            if ($offsetDays >= $limit['lower'] && $offsetDays < $limit['higher']) {
                $bucketId = $rangeId;
                break;
            }
        }

        return $bucketId;
    }
}