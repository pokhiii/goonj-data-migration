<?php

namespace Pokhi\CsvTransformer\Transformers;

use Pokhi\CsvTransformer\Traits\Configurable;

class VolMotivationsTransformer extends Transformer
{
    use Configurable;

    public function __construct($value)
    {
        parent::__construct($value);
        $this->initializeConfig('motivation_mappings.json');
    }

    public function getValue()
    {
        if (empty($this->value)) {
            return $this->value; // Return as is if the value is empty
        }

        $motivations = explode(';', $this->value);
        $mappedMotivations = [];

        foreach ($motivations as $motivation) {
            $motivation = trim($motivation); // Trim whitespace

            if (isset($this->mappings[$motivation])) {
                $mappedMotivations[] = $this->mappings[$motivation];
            } else {
                echo "Enter the CiviCRM value for '{$motivation}': ";
                $handle = fopen("php://stdin", "r");
                $civiValue = trim(fgets($handle));
                fclose($handle);

                $this->saveMapping($motivation, $civiValue);
                $mappedMotivations[] = $civiValue;
            }
        }

        return implode(', ', $mappedMotivations);
    }
}
