<?php

/**
    Display % from all users being subscribed for < 1 day
    Display % from all users being subscribed for 1-3 days
    Display % from all users being subscribed for 3-7 days
    Display % from all users being subscribed for 7-14 days
    Display % from all users being subscribed for 14-21 days
    Display % from all users being subscribed for 21-28 days
    Display % from all users being subscribed for >28 days
*/
return [
    1 => [
        'lower' => 0,
        'higher' => 4,
        'label' => '< 4 day',
    ],
    2 => [
        'lower' => 4,
        'higher' => 10,
        'label' => '4-10 days',
    ],
    3 => [
        'lower' => 10,
        'higher' => PHP_INT_MAX,
        'label' => '> 10 days',
    ],
];

