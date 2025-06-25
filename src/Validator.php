<?php

namespace Hexlet\Validator;

class Validator
{
    public function string(): StringScheme
    {
        return new StringScheme();
    }
}