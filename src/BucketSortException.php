<?php

namespace LifetimeBucketSort;

/**
 * Class BucketSortException
 *
 * @package LifetimeBucketSort
 */
class BucketSortException extends \RuntimeException
{
    const BAD_REFERENCE_DATE_TITLE = 'Bad reference date selected.';
    const BAD_REFERENCE_DATE_CODE = 10;

    const BAD_DIFF_DATE_TITLE = 'Bad creation date provided.';
    const BAD_DIFF_DATE_CODE = 20;

    const REFERENCE_DATE_AFTER_SECONDARY_DATE_TITLE = 'The secondary date is before the reference date.';
    const REFERENCE_DATE_AFTER_SECONDARY_DATE_CODE = 30;

    const SECONDARY_DATE_MISSING_TITLE = 'The secondary date is missing in the dataset.';
    const SECONDARY_DATE_MISSING_CODE = 40;
}