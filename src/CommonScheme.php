<?php

namespace Hexlet\Validator;

abstract class CommonScheme
{
    protected array $validators = [];
    protected array $customValidators = [];

    public function __construct(array $customValidators = [])
    {
        $this->customValidators = $customValidators;
    }

    public function required(): static
    {
        $this->rules['required'] = true;
        return $this;
    }

    public function checkCustomValidators(mixed $value): bool
    {
        foreach ($this->validators as $validator) {
            $c = $validator($value);

            var_dump($value);
            var_dump($c);

            echo "------\n\n";

            if (!c) {
            //if (!$validator($value)) {
                return false;
            }
        }

        return true;
    }

    public function test(string $name, mixed ...$args): self
    {
        $this->validators[$name] = function ($value) use ($name, $args) {
            $validator = $this->customValidators[$name];

            return $validator($value, ...$args);
        };

var_dump($this->validators[$name]);

        return $this;
    }
}