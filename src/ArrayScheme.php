<?php

namespace Hexlet\Validator;

class ArrayScheme
{
    private $rules = [
        'required' => false,
        'sizeof' => false
    ];

    public function required(): static
    {
        $this->rules['required'] = true;
        return $this;
    }

    public function sizeof(int $size): static
    {
        $this->rules['sizeof'] = $size;
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
                    case 'sizeof':
                        if (sizeof($array) < $value) {
                            return false;
                        }
                        break;
                }
            }
        }

        return true;
    }
}