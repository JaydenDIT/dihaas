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
    'class_x' => [
        'required' => false,
        'required_if' => [
            ['applicant_qualifications', 'class_x', '=', true],
        ],
    ],
];
