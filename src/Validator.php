<?php

namespace Hexlet\Validator;

class Validator
{
    private array $customValidators = [
        'string' => [],
        'number' => [],
        'array' => []
    ];

    public function string(): StringScheme
    {
        return new StringScheme($this->getCustomValidators('string'));
    }

    public function number(): NumberScheme
    {
        return new NumberScheme($this->getCustomValidators('number'));
    }

    public function array(): ArrayScheme
    {
        return new ArrayScheme($this->getCustomValidators('array'));
    }

    public function addValidator(string $type, string $name, callable $fn): void
    {
        if (!array_key_exists($type, $this->customValidators)) {
            var_dump($type);
            echo "Unsupported!";
            throw new \InvalidArgumentException("Unsupported validator type: {$type}");
        }
        $this->customValidators[$type][$name] = $fn;
        var_dump($name);
    }

    public function getCustomValidators(string $type): array
    {
        return $this->customValidators[$type] ?? [];
    }
}