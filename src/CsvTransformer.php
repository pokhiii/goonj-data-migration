<?php

namespace Pokhi\CsvTransformer;

class CsvTransformer
{
    private $inputFile;
    private $outputFile;
    private $headers;
    private $columnsToInclude = [];
    private $newColumnNames = [];

    public function __construct($inputFile, $outputFile)
    {
        $this->inputFile = $inputFile;
        $this->outputFile = $outputFile;
    }

    public function run()
    {
        $this->selectColumns();
        $this->transform();
    }

    private function selectColumns()
    {
        $inputHandle = fopen($this->inputFile, 'r');
        if (!$inputHandle) {
            throw new \Exception("Cannot read input file: {$this->inputFile}");
        }

        $this->headers = fgetcsv($inputHandle);
        if ($this->headers === false) {
            fclose($inputHandle);
            throw new \Exception("Failed to read the header row from input file: {$this->inputFile}");
        }

        fclose($inputHandle);

        foreach ($this->headers as $index => $header) {
            echo "Include column '{$header}' in the output? (y/n, default is 'y'): ";
            $include = trim(fgets(STDIN));
            if (strtolower($include) === 'y' || $include === '') {
                $this->columnsToInclude[] = $index;
                echo "Enter new name for column '{$header}' (press enter to keep the same): ";
                $newName = trim(fgets(STDIN));
                $this->newColumnNames[$index] = $newName ?: $header;
            }
        }
    }

    private function transform()
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

        // Write the new headers to the output file
        $newHeaders = array_map(function ($index) {
            return $this->newColumnNames[$index];
        }, $this->columnsToInclude);
        fputcsv($outputHandle, $newHeaders);

        // Skip the header row
        fgetcsv($inputHandle);

        // Loop over each row in the input CSV
        while (($row = fgetcsv($inputHandle)) !== false) {
            $newRow = [];
            foreach ($this->columnsToInclude as $index) {
                $newRow[] = $this->transformValue($this->headers[$index], $row[$index]);
            }
            fputcsv($outputHandle, $newRow);
        }

        fclose($inputHandle);
        fclose($outputHandle);

        echo "Transformation complete. Output file: {$this->outputFile}\n";
    }

    private function transformValue($header, $value)
    {
        $value = trim($value);

        if ($header === 'Name') {
            $value = strtoupper($value);
        } elseif ($header === 'Activities') {
            $value = str_replace(';', ',', $value);
            if (strpos($value, ',') !== false) {
                $value = '"' . $value . '"';
            }
        } elseif ($header === 'State') {
            $states = [
                'DELHI' => 'DL',
                'Delhi' => 'DL',
                'DL' => 'DL',
                'delhi' => 'DL',
                // Add more mappings as needed
            ];
            $value = $states[$value] ?? $value;
        }

        return $value;
    }
}