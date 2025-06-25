<?php

namespace Hexlet\Validator;

class StringScheme
{
    private $rules = [
        'required' => false,
        'contains' => false,
        'minlength' => false
    ];

    public function required(): static
    {
        $this->rules['required'] = true;
        return $this;
    }

    public function minLength(int $length): static
    {
        $this->rules['minlength'] = $length;
        return $this;
    }

    public function contains(string $substring): static
    {
        $this->rules['contains'] = $substring;
        return $this;
    }

    public function isValid(string $string): bool
    {
        foreach ($this->rules as $rule => $value) {
            if ($value !== false) {
                switch ($rule) {
                    case 'required':
                        if (
                            $str === null
                            || $str == ''
                        ) {
                            return false;
                        }
                        break;
                    case 'minlength':
                        if (strlen($string) < $value) {
                            return false;
                        }
                        break;
                    case 'contains':
                        if (str_contains($string, $value) === false) {
                            return false;
                        }
                }
            }
        }

        return true;
    }
}