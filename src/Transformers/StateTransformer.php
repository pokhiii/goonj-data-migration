<?php

namespace Pokhi\CsvTransformer\Transformers;

use Pokhi\CsvTransformer\ConfigManager;

class StateTransformer extends Transformer
{
    private $configManager;
    private $stateMappings;

    public function __construct($value)
    {
        parent::__construct($value);
        $this->configManager = new ConfigManager('state_mappings.json');
        $this->stateMappings = $this->configManager->getMappings();
    }

    public function getValue()
    {
        if (isset($this->stateMappings[$this->value])) {
            return $this->stateMappings[$this->value];
        }

        echo "Enter the standard value for '{$this->value}': ";
        $handle = fopen("php://stdin", "r");
        $newValue = trim(fgets($handle));
        fclose($handle);

        $this->stateMappings[$this->value] = $newValue;
        $this->configManager->saveMappings($this->stateMappings);

        return $newValue;
    }
}
