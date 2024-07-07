#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pokhi\CsvTransformer\CsvTransformer;

if ($argc !== 3) {
    echo "Usage: php transform.php <inputFile> <outputFile>\n";
    exit(1);
}

$inputFile = $argv[1];
$outputFile = $argv[2];

try {
    $transformer = new CsvTransformer($inputFile, $outputFile);
    $transformer->run();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}