<?php

namespace Pokhi\CsvTransformer\Transformers;

use Pokhi\CsvTransformer\Traits\Configurable;

class CountryTransformer extends Transformer
{
    use Configurable;

    public function __construct($value)
    {
        parent::__construct($value);
        $this->initializeConfig('country_mappings.json');
    }

    public function getValue()
    {
        if (isset($this->mappings[$this->value])) {
            return $this->mappings[$this->value];
        }

        echo "Enter the standard value for '{$this->value}': ";
        $handle = fopen("php://stdin", "r");
        $newValue = trim(fgets($handle));
        fclose($handle);

        $this->mappings[$this->value] = $newValue;
        $this->configManager->saveMappings($this->mappings);

        return $newValue;
    }
}
