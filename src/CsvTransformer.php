<?php

namespace Pokhi\CsvTransformer;

use Pokhi\CsvTransformer\Transformers\StateTransformer;
use Pokhi\CsvTransformer\Transformers\VolActivitiesTransformer;
use Pokhi\CsvTransformer\Transformers\VolSkillsTransformer;
use Pokhi\CsvTransformer\Transformers\VolMotivationsTransformer;
use Pokhi\CsvTransformer\Transformers\VolHourTransformer;

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
        $columnTransformers = [
            'Mailing State/Province' => StateTransformer::class,
            'Which activities are you interested in?' => VolActivitiesTransformer::class,
            'Volunteer Skills' => VolSkillsTransformer::class,
            'Motivates you to Volunteer' => VolMotivationsTransformer::class,
            'How many hours can you volunteer?' => VolHourTransformer::class,
        ];

        if (isset($columnTransformers[$header])) {
            $transformerClass = $columnTransformers[$header];
            $transformer = new $transformerClass($value);
            return $transformer->getValue();
        }

        return $value;
    }
}
