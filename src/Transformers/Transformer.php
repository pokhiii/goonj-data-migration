<?php

namespace Pokhi\CsvTransformer\Transformers;

abstract class Transformer
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    abstract public function getValue();
}