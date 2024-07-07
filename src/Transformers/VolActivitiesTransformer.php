<?php

namespace Pokhi\CsvTransformer\Transformers;

class VolActivitiesTransformer extends Transformer
{
    public function getValue()
    {
        $value = str_replace(';', ',', $this->value);
        if (strpos($value, ',') !== false) {
            $value = '"' . $value . '"';
        }
        return $value;
    }
}
