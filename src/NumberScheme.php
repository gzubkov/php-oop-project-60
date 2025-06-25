<?php

namespace Hexlet\Validator;

class NumberScheme extends CommonScheme
{
    private $rules = [
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
        foreach ($this->rules as $rule => $value) {
            if ($value !== false) {
                switch ($rule) {
                    case 'required':
                        if (is_int($integer) === false) {
                            return false;
                        }
                        break;
                    case 'positive':
                        if ($integer < 0) {
                            return false;
                        }
                        break;
                    case 'range':
                        if (
                            $integer < $value[0]
                            || $integer > $value[1]
                        ) {
                            return false;
                        }
                }
            }
        }

        return $this->checkCustomValidators($integer);
    }
}