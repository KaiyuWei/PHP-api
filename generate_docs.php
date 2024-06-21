<?php

require __DIR__ . '/vendor/autoload.php';

use OpenApi\Generator;

$openapi = Generator::scan([__DIR__ . '/app/Controllers']);

file_put_contents(__DIR__ . '/public/documentation/openapi.json', $openapi->toJson());

function calculate(string $str): int
{
    return 3;
}