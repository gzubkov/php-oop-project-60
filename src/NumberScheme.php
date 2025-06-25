<?php

namespace Hexlet\Validator;

class NumberScheme extends CommonScheme
{
    protected $rules = [
        'required' => false,
        'positive' => false,
        'range' => false
    ];

    public function positive(): static
    {
        $this->rules['positive'] = true;
        return $this;
    }

    public function range(int $min, int $max): static
    {
        $this->rules['range'] = [$min, $max];
        return $this;
    }

    public function isValid($integer): bool
    {
        var_dump($integer);
        var_dump($rules);
        echo "----\n";

        if (!is_null($integer) && !is_numeric($integer)) {
            return false;
        }
        
        foreach ($this->rules as $rule => $value) {
            if ($value !== false) {
                switch ($rule) {
                    case 'required':
                        if (is_numeric($integer) === false) {
                            return false;
                        }
                        break;
                    case 'positive':
                        if (
                            $integer <= 0
                            && $integer !== null
                        ) {
                            return false;
                        }
                        break;
                    case 'range':
                        if ($integer === null) {
                            return false;
                        }
                        if (
                            $integer < $value[0]
                            || $integer > $value[1]
                        ) {
                            return false;
                        }
                }
            }
        }

        return true; // $this->checkCustomValidators($integer);
    }
}