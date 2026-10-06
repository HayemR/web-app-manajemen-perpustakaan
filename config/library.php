<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Loan Duration (in Days)
    |--------------------------------------------------------------------------
    |
    | The default number of days a book can be borrowed before being due.
    |
    */
    'loan_duration_days' => env('LIBRARY_LOAN_DURATION_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Fine Rate per Day (in Rupiah)
    |--------------------------------------------------------------------------
    |
    | The late fee per day charged when returning a book past its due date.
    |
    */
    'fine_per_day' => env('LIBRARY_FINE_PER_DAY', 1000),
];
