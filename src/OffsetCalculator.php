<?php

namespace LifetimeBucketSort;

/**
 * Class OffsetCalculator
 *
 * @package LifetimeBucketSort
 */
final class OffsetCalculator
{
    /**
     * The number of seconds in a single day
     *
     * @const integer
     */
    private const SINGLE_DAY = 86400;

    /**
     * @var \DateTime
     */
    private $referenceDate;

    /**
     * @var integer
     */
    private $referenceTimestamp;

    /**
     * BucketSort constructor.
     */
    public function __construct()
    {
        $this->setDefaultSettings();
    }

    /**
     * Set reference date
     *
     * @param string $referenceDate
     * @throws BucketSortException
     */
    public function setReferenceDate(string $referenceDate)
    {
        try {
            $this->referenceDate = new \DateTime($referenceDate);
        } catch (\Exception $e) {
            throw new BucketSortException(
                BucketSortException::BAD_REFERENCE_DATE_TITLE,
                BucketSortException::BAD_REFERENCE_DATE_CODE
            );
        }
        $this->referenceTimestamp = $this->referenceDate->format('U');
    }

    /**
     * @param string $secondaryDate
     * @return int
     * @throws BucketSortException
     */
    public function calculateOffsetInDays(string $secondaryDate): int
    {
        try {
            $substringDate = new \DateTime($secondaryDate);
        } catch (\Exception $e) {
            throw new BucketSortException(
                BucketSortException::BAD_DIFF_DATE_TITLE,
                BucketSortException::BAD_DIFF_DATE_CODE
            );
        }

        $createdTimestamp = $substringDate->format('U');
        $diffTimestamp = $this->referenceTimestamp - $createdTimestamp;

        return (int) floor($diffTimestamp / self::SINGLE_DAY);
    }

    /**
     * Set a default reference date to today
     */
    private function setDefaultSettings()
    {
        $this->setReferenceDate('now');
    }
}