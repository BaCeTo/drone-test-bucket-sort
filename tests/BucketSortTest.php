<?php

use LifetimeBucketSort\BucketSort;
use LifetimeBucketSort\BucketSortException;
use PHPUnit\Framework\TestCase;

/**
 * Class BucketSortTest
 */
class BucketSortTest extends TestCase
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

    public function testBucketSort()
    {
        $bucketSort = new BucketSort($this->getBucketSettings());

        $bucketId = $bucketSort->getBucketId(1);
        $this->assertEquals(1, $bucketId);

        $bucketId = $bucketSort->getBucketId(1);
        $this->assertEquals(1, $bucketId);

        $bucketId = $bucketSort->getBucketId(3);
        $this->assertEquals(1, $bucketId);

        $bucketId = $bucketSort->getBucketId(6);
        $this->assertEquals(2, $bucketId);

        $bucketId = $bucketSort->getBucketId(8);
        $this->assertEquals(2, $bucketId);

        $bucketId = $bucketSort->getBucketId(29);
        $this->assertEquals(3, $bucketId);

        $bucketId = $bucketSort->getBucketId(365);
        $this->assertEquals(3, $bucketId);

        $bucketId = $bucketSort->getBucketId(10000);
        $this->assertEquals(3, $bucketId);
    }

    public function testBucketSortException()
    {
        $bucketSort = new BucketSort($this->getBucketSettings());

        $this->expectException(BucketSortException::class);
        $this->expectExceptionCode(BucketSortException::REFERENCE_DATE_AFTER_SECONDARY_DATE_CODE);
        $this->expectExceptionMessage(BucketSortException::REFERENCE_DATE_AFTER_SECONDARY_DATE_TITLE);

        $bucketSort->getBucketId(-3);
    }

}