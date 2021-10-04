<?php

use LifetimeBucketSort\OffsetCalculator;
use LifetimeBucketSort\BucketSortException;
use PHPUnit\Framework\TestCase;

/**
 * Class OffsetCalculateTest
 */
class OffsetCalculateTest extends TestCase
{
    public function testBadReferenceDate()
    {
        $offsetCalculator = new OffsetCalculator();

        $this->expectException(BucketSortException::class);
        $this->expectExceptionCode(BucketSortException::BAD_REFERENCE_DATE_CODE);
        $this->expectExceptionMessage(BucketSortException::BAD_REFERENCE_DATE_TITLE);

        $offsetCalculator->setReferenceDate('aaaaa');
    }

    public function testBadDiffDate()
    {
        $offsetCalculator = new OffsetCalculator();
        $offsetCalculator->setReferenceDate('2019-09-25');

        $this->expectException(BucketSortException::class);
        $this->expectExceptionCode(BucketSortException::BAD_DIFF_DATE_CODE);
        $this->expectExceptionMessage(BucketSortException::BAD_DIFF_DATE_TITLE);

        $offsetCalculator->calculateOffsetInDays('bbbb');
    }

    /**
     * Simple calculation
     */
    public function testCalculateSimpleOffset()
    {
        $offsetCalculator = new OffsetCalculator();

        // A 31-day month
        $offsetCalculator->setReferenceDate('2019-09-01');
        $diffDays = $offsetCalculator->calculateOffsetInDays('2019-08-01');
        $this->assertEquals(31, $diffDays);

        // A 29-day month in a leap year, with a negative offset
        $offsetCalculator->setReferenceDate('2016-02-01');
        $diffDays = $offsetCalculator->calculateOffsetInDays('2016-03-01');
        $this->assertEquals(-29, $diffDays);

        // Calculate over a leap year
        $offsetCalculator->setReferenceDate('2017-01-01');
        $diffDays = $offsetCalculator->calculateOffsetInDays('2016-01-01');
        $this->assertEquals(366, $diffDays);

        // Calculate the same date
        $offsetCalculator->setReferenceDate('2017-01-01');
        $diffDays = $offsetCalculator->calculateOffsetInDays('2017-01-01');
        $this->assertEquals(0, $diffDays);
    }
}