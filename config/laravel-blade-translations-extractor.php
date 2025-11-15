<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Translation Pattern
    |--------------------------------------------------------------------------
    |
    | The regex pattern used to extract translation strings from Blade and PHP
    | files. This pattern default to matche __() and @lang() helper functions.
    |
    */
    'pattern' => '/(?:__|@lang)\(\s*(["\'])(.*?)\1\s*[\),]/m',
];

