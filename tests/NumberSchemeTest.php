<?php

namespace Hexlet\Validator\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;
use Hexlet\Validator\Validator;

class NumberValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    public function testBasicValidation(): void
    {
        $schema = $this->validator->number();

        $this->assertTrue($schema->isValid(null));
        $this->assertTrue($schema->isValid(0));
        $this->assertTrue($schema->isValid(10));
    }

    public function testRequired(): void
    {
        $schema = $this->validator->number()->required();

        $this->assertFalse($schema->isValid(null));
        $this->assertTrue($schema->isValid(0));
        $this->assertTrue($schema->isValid(10));
    }

    public function testPositive(): void
    {
        $schema = $this->validator->number()->positive();

        $this->assertTrue($schema->isValid(null));
        $this->assertFalse($schema->isValid(0));
        $this->assertFalse($schema->isValid(-10));
        $this->assertTrue($schema->isValid(10));
    }

    public function testRange(): void
    {
        $schema = $this->validator->number()->range(-5, 5);

        $this->assertTrue($schema->isValid(null));
        $this->assertTrue($schema->isValid(-5));
        $this->assertTrue($schema->isValid(5));
        $this->assertFalse($schema->isValid(-6));
        $this->assertFalse($schema->isValid(6));
    }

    public function testCombinedValidators(): void
    {
        $schema = $this->validator->number()
            ->required()
            ->positive()
            ->range(5, 10);

        $this->assertFalse($schema->isValid(null));
        $this->assertFalse($schema->isValid(0));
        $this->assertFalse($schema->isValid(4));
        $this->assertTrue($schema->isValid(5));
        $this->assertTrue($schema->isValid(7));
        $this->assertTrue($schema->isValid(10));
        $this->assertFalse($schema->isValid(11));
    }

    public function testCustomNumberValidator(): void
    {
        $v = new Validator();
        $v->addValidator('number', 'min', fn($value, $min) => $value >= $min);

        $schema = $v->number()->test('min', 5);
        $this->assertFalse($schema->isValid(4));
        $this->assertTrue($schema->isValid(6));
        $this->assertTrue($schema->isValid(null)); // null allowed by default
    }

    public function testRequiredWithCustomValidator(): void
    {
        $v = new Validator();
        $v->addValidator('number', 'even', fn($value) => $value % 2 === 0);

        $schema = $v->number()->required()->test('even');
        $this->assertFalse($schema->isValid(null));
        $this->assertFalse($schema->isValid(3));
        $this->assertTrue($schema->isValid(4));
    }
}
