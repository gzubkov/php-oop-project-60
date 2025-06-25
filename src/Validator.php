<?php

namespace Hexlet\Validator;

class Validator
{
    public function string(): StringScheme
    {
        return new StringScheme();
    }

    public function number(): NumberScheme
    {
        return new NumberScheme();
    }

    public function array(): ArrayScheme
    {
        return new ArrayScheme();
    }
}