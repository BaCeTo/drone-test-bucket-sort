<?php

use LifetimeBucketSort\BucketSort;
use LifetimeBucketSort\BucketSortException;
use LifetimeBucketSort\BucketSortMultiple;
use LifetimeBucketSort\OffsetCalculator;
use PHPUnit\Framework\TestCase;

/**
 * Class BucketSortMultipleTest
 */
class BucketSortMultipleTest extends TestCase
{
    /**
     * @var array
     */
    private static $bucketSettings;

    /**
     * @return array
     */
    private function getBucketSettings()
    {
        if (is_null(self::$bucketSettings)) {
            self::$bucketSettings = include(__DIR__ . './../src/DefaultBucketSettings.php');
        }
        return self::$bucketSettings;
    }

    /**
     * @return BucketSort
     */
    private function getBucketSort()
    {
        return new BucketSort($this->getBucketSettings());
    }

    /**
     * @param string|null $referenceDate
     * @return OffsetCalculator
     */
    private function getOffsetCalculator(?string $referenceDate = null)
    {
        $offsetCalculator = new OffsetCalculator();
        if ($referenceDate) {
            $offsetCalculator->setReferenceDate($referenceDate);
        }

        return $offsetCalculator;
    }

    public function testBatchCalculateException()
    {
        $bucketSort = $this->getBucketSort();
        $offsetCalculator = $this->getOffsetCalculator('2019-09-25');

        $sortMultiple = new BucketSortMultiple($bucketSort, $offsetCalculator);
        $sortMultiple->setDateColumnName(BucketSortMultiple::DEFAULT_DATE_COLUMN);

        $testArray = [
            [
                'date_created' => '2019-09-23', // CorrectData
                'other' => []
            ],
            [
                'dateCreated' => '2019-09-23', // WillThrowException
                'data' => []
            ]
        ];

        $this->expectException(BucketSortException::class);
        $this->expectExceptionCode(BucketSortException::SECONDARY_DATE_MISSING_CODE);
        $this->expectExceptionMessage(BucketSortException::SECONDARY_DATE_MISSING_TITLE);

        $buckets = [];
        foreach ($sortMultiple->batchCalculate($testArray) as $bucketId) {
            if (!isset($buckets[$bucketId])) {
                $buckets[$bucketId] = 0;
            }

            $buckets[$bucketId]++;
        }
    }

    public function testBatchCalculate()
    {
        $bucketSort = $this->getBucketSort();
        $offsetCalculator = $this->getOffsetCalculator('2019-09-25');

        $sortMultiple = new BucketSortMultiple($bucketSort, $offsetCalculator);
        $sortMultiple->setDateColumnName(BucketSortMultiple::DEFAULT_DATE_COLUMN);

        $testArray = [
            [
                'date_created' => '2019-09-25', // Bucket 1
                'other' => []
            ],
            [
                'date_created' => '2019-09-23', // Bucket 1
                'data' => []
            ],
            [
                'date_created' => '2019-09-24', // Bucket 1
                'data' => []
            ],
            [
                'date_created' => '2019-09-24', // Bucket 1
                'data' => []
            ],
            [
                'date_created' => '2019-09-20', // Bucket 2
                'data' => []
            ],
            [
                'date_created' => '2019-08-17', // Bucket 3
                'data' => []
            ],
        ];

        $buckets = [];
        foreach ($sortMultiple->batchCalculate($testArray) as $bucketId) {
            if (!isset($buckets[$bucketId])) {
                $buckets[$bucketId] = 0;
            }

            $buckets[$bucketId]++;
        }

        $this->assertEquals([
            1 => 4,
            2 => 1,
            3 => 1,
        ], $buckets);

        $this->assertEquals([
            '2019-09-25' => 1,
            '2019-09-23' => 1,
            '2019-09-24' => 1,
            '2019-09-20' => 2,
            '2019-08-17' => 3,
        ], $sortMultiple->getDateBucketMap());
    }
}