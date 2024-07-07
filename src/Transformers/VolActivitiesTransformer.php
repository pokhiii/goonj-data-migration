<?php

namespace Pokhi\CsvTransformer\Transformers;

use Pokhi\CsvTransformer\ConfigManager;

class VolActivitiesTransformer extends Transformer
{
    private $configManager;
    private $activityMappings;

    public function __construct($value)
    {
        parent::__construct($value);
        $this->configManager = new ConfigManager('activity_mappings.json');
        $this->activityMappings = $this->configManager->getMappings();
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

            if (isset($this->activityMappings[$activity])) {
                $mappedActivities[] = $this->activityMappings[$activity];
            } else {
                echo "Enter the CiviCRM value for '{$activity}': ";
                $handle = fopen("php://stdin", "r");
                $civiValue = trim(fgets($handle));
                fclose($handle);

                $this->activityMappings[$activity] = $civiValue;
                $this->configManager->saveMappings($this->activityMappings);

                $mappedActivities[] = $civiValue;
            }
        }

        return implode(', ', $mappedActivities);
    }
}
