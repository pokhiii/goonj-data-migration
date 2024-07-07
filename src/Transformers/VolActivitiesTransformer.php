<?php

namespace Pokhi\CsvTransformer\Transformers;

use Pokhi\CsvTransformer\Traits\Configurable;

class VolActivitiesTransformer extends Transformer
{
    use Configurable;

    public function __construct($value)
    {
        parent::__construct($value);
        $this->initializeConfig('activity_mappings.json');
    }

    public function getValue()
    {
        if (empty($this->value)) {
            return $this->value; // Return as is if the value is empty
        }

        $activities = explode(';', $this->value);
        $mappedActivities = [];

        foreach ($activities as $activity) {
            $activity = trim($activity); // Trim whitespace

            if (isset($this->mappings[$activity])) {
                $mappedActivities[] = $this->mappings[$activity];
            } else {
                echo "Enter the CiviCRM value for '{$activity}': ";
                $handle = fopen("php://stdin", "r");
                $civiValue = trim(fgets($handle));
                fclose($handle);

                $this->saveMapping($activity, $civiValue);
                $mappedActivities[] = $civiValue;
            }
        }

        return implode(', ', $mappedActivities);
    }
}
