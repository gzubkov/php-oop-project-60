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

        Assert::assertTrue($schema->isValid(null));
        Assert::assertTrue($schema->isValid(0));
        Assert::assertTrue($schema->isValid(10));
    }

    public function testRequired(): void
    {
        $schema = $this->validator->number()->required();

        Assert::assertFalse($schema->isValid(null));
        Assert::assertTrue($schema->isValid(0));
        Assert::assertTrue($schema->isValid(10));
    }

    public function testPositive(): void
    {
        $schema = $this->validator->number()->positive();

        Assert::assertTrue($schema->isValid(null));
        Assert::assertFalse($schema->isValid(0));
        Assert::assertFalse($schema->isValid(-10));
        Assert::assertTrue($schema->isValid(10));
    }

    public function testRange(): void
    {
        $schema = $this->validator->number()->range(-5, 5);

        Assert::assertTrue($schema->isValid(null));
        Assert::assertTrue($schema->isValid(-5));
        Assert::assertTrue($schema->isValid(5));
        Assert::assertFalse($schema->isValid(-6));
        Assert::assertFalse($schema->isValid(6));
    }

    public function testCombinedValidators(): void
    {
        $schema = $this->validator->number()
            ->required()
            ->positive()
            ->range(5, 10);

        Assert::assertFalse($schema->isValid(null));
        Assert::assertFalse($schema->isValid(0));
        Assert::assertFalse($schema->isValid(4));
        Assert::assertTrue($schema->isValid(5));
        Assert::assertTrue($schema->isValid(7));
        Assert::assertTrue($schema->isValid(10));
        Assert::assertFalse($schema->isValid(11));
    }

    public function testCustomNumberValidator(): void
    {
        $v = new Validator();
        $v->addValidator('number', 'min', fn($value, $min) => $value >= $min);

        $schema = $v->number()->test('min', 5);
        Assert::assertFalse($schema->isValid(4));
        Assert::assertTrue($schema->isValid(6));
        Assert::assertTrue($schema->isValid(null)); // null allowed by default
    }

    public function testRequiredWithCustomValidator(): void
    {
        $v = new Validator();
        $v->addValidator('number', 'even', fn($value) => $value % 2 === 0);

        $schema = $v->number()->required()->test('even');
        Assert::assertFalse($schema->isValid(null));
        Assert::assertFalse($schema->isValid(3));
        Assert::assertTrue($schema->isValid(4));
    }
}
