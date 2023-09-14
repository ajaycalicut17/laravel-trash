<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pruning Trash Models
    |--------------------------------------------------------------------------
    |
    | Here you may specify the pruning status and period.
    |
    */

    'pruning' => [
        'status' => false,
        'period' => now()->subMonth(),
    ],
];
