<?php

namespace Pokhi\CsvTransformer\Transformers;

use Pokhi\CsvTransformer\Traits\Configurable;

class VolSkillsTransformer extends Transformer
{
    use Configurable;

    public function __construct($value)
    {
        parent::__construct($value);
        $this->initializeConfig('skills_mappings.json');
    }

    public function getValue()
    {
        if (empty($this->value)) {
            return $this->value; // Return as is if the value is empty
        }

        $skills = explode(';', $this->value);
        $mappedSkills = [];

        foreach ($skills as $skill) {
            $skill = trim($skill); // Trim whitespace

            if (isset($this->mappings[$skill])) {
                $mappedSkills[] = $this->mappings[$skill];
            } else {
                echo "Enter the CiviCRM value for '{$skill}': ";
                $handle = fopen("php://stdin", "r");
                $civiValue = trim(fgets($handle));
                fclose($handle);

                $this->saveMapping($skill, $civiValue);
                $mappedSkills[] = $civiValue;
            }
        }

        return implode(', ', $mappedSkills);
    }
}
