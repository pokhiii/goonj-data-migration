<?php

namespace Pokhi\CsvTransformer;

class ConfigManager
{
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    public function getMappings()
    {
        return json_decode(file_get_contents($this->filePath), true);
    }

    public function saveMappings($mappings)
    {
        file_put_contents($this->filePath, json_encode($mappings, JSON_PRETTY_PRINT));
    }
}
