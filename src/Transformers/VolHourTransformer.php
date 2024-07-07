<?php

namespace Pokhi\CsvTransformer\Transformers;

class VolHourTransformer extends Transformer
{
    public function getValue()
    {
        // Implement transformation logic for How many hours can you volunteer? column
        return $this->value;
    }
}
