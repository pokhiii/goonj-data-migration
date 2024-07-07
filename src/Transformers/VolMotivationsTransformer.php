<?php

namespace Pokhi\CsvTransformer\Transformers;

class VolMotivationsTransformer extends Transformer
{
    public function getValue()
    {
        // Implement transformation logic for Motivates you to Volunteer column
        return $this->value;
    }
}