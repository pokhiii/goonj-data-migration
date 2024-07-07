<?php

namespace Pokhi\CsvTransformer\Traits;

use Pokhi\CsvTransformer\ConfigManager;

trait Configurable
{
    protected $configManager;
    protected $mappings;

    protected function initializeConfig($configFile)
    {
        $this->configManager = new ConfigManager($configFile);
        $this->mappings = $this->configManager->getMappings();
    }

    protected function saveMapping($key, $value)
    {
        $this->mappings[$key] = $value;
        $this->configManager->saveMappings($this->mappings);
    }
}
