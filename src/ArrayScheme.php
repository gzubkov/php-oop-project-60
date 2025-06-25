<?php

namespace Hexlet\Validator;

class ArrayScheme extends CommonScheme
{
    protected $rules = [
        'required' => false,
        'sizeof' => false,
        'shape' => false
    ];

    public function sizeof(int $size): static
    {
        $this->rules['sizeof'] = $size;
        return $this;
    }

    public function shape(array $shapes): static
    {
        $this->rules['shape'] = $shape;
        return $this;
    }

    public function isValid($array): bool
    {
        foreach ($this->rules as $rule => $value) {
            if ($value !== false) {
                switch ($rule) {
                    case 'required':
                        if (is_array($array) === false) {
                            return false;
                        }
                        break;
                    /*case 'sizeof':
                        if (sizeof($array) < $value) {
                            return false;
                        }
                        break;
                    case 'shape':
                        foreach ($value as $key => $innerRule) {
                            if (array_key_exists($key, $array) === false) {
                                return false;
                            }
                            
                            if ($innerRule->isValid($value[$key]) === false) {
                                return false;
                            }
                        } */
                }
            }
        }

        return $this->checkCustomValidators($array);;
    }
}