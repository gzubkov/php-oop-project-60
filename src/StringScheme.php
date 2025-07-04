<?php

namespace Hexlet\Validator;

class StringScheme extends CommonScheme
{
    protected array $rules = [
        'required' => false,
        'contains' => false,
        'minlength' => false
    ];

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

    public function isValid(mixed $string): bool
    {
        foreach ($this->rules as $rule => $value) {
            if ($value !== false) {
                switch ($rule) {
                    case 'required':
                        if (
                            is_string($string) === false
                            || strlen($string) === 0
                        ) {
                            return false;
                        }
                        break;
                    case 'minlength':
                        if (mb_strlen($string) < $value) {
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

        return $this->checkCustomValidators($string);
    }
}
