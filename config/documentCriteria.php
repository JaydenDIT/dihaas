<?php

return [
    'compulsory' => [
        'required' => true, // always required
        'required_if' => [], // no condition
    ],
    'caste' => [
        'required' => false, // not always required
        'required_if' => [
            ['proforma', 'caste_id', 'in', [1, 2, 3, 4]],
        ],
    ],
    'handicapped' => [
        'required' => false,
        'required_if' => [
            ['proforma', 'physically_handicapped', '=', true],
        ],
    ],
    'optional' => [
        'required' => false, // not required by default
        'required_if' => [], // no condition
    ],
    'dead_on_duty' => [
        'required' => false,
        'required_if' => [
            ['proforma', 'expire_on_duty', '=', true],
        ],
    ],

];
