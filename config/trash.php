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

    'pruning_status' => false,
    'pruning_period' => now()->subMonth(),
];
