<?php

namespace Pokhi\CsvTransformer;

class CsvTransformer
{
    private $inputFile;
    private $outputFile;
    private $headers;

    public function __construct($inputFile, $outputFile)
    {
        $this->inputFile = $inputFile;
        $this->outputFile = $outputFile;
    }

    public function transform()
    {
        $inputHandle = fopen($this->inputFile, 'r');
        if (!$inputHandle) {
            throw new \Exception("Cannot read input file: {$this->inputFile}");
        }

        $outputHandle = fopen($this->outputFile, 'w');
        if (!$outputHandle) {
            fclose($inputHandle);
            throw new \Exception("Cannot open output file: {$this->outputFile}");
        }

        $this->headers = fgetcsv($inputHandle);
        if ($this->headers === false) {
            fclose($inputHandle);
            fclose($outputHandle);
            throw new \Exception("Failed to read the header row from input file: {$this->inputFile}");
        }

        fputcsv($outputHandle, $this->headers);

        while (($row = fgetcsv($inputHandle)) !== false) {
            foreach ($row as $index => &$cell) {
                $cell = $this->transformValue($this->headers[$index], $cell);
            }
            fputcsv($outputHandle, $row);
        }

        fclose($inputHandle);
        fclose($outputHandle);

        echo "Transformation complete. Output file: {$this->outputFile}\n";
    }

    private function transformValue($header, $value)
    {
        echo "header: " . $header . PHP_EOL;
        echo "value: " . $value . PHP_EOL;

        return $value;
    }
}
