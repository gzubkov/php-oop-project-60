<?php

namespace Hexlet\Validator\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;
use Hexlet\Validator\Validator;

class ArrayValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    public function testBasicValidation(): void
    {
        $schema = $this->validator->array();

        Assert::assertTrue($schema->isValid(null));
        Assert::assertTrue($schema->isValid([]));
        Assert::assertTrue($schema->isValid(['hexlet']));
    }

    public function testRequired(): void
    {
        $schema = $this->validator->array()->required();

        Assert::assertFalse($schema->isValid(null));
        Assert::assertTrue($schema->isValid([]));
        Assert::assertTrue($schema->isValid(['hexlet']));
    }

    public function testSizeof(): void
    {
        $schema = $this->validator->array()->sizeof(2);

        Assert::assertTrue($schema->isValid(null)); // null allowed when not required
        Assert::assertFalse($schema->isValid(['hexlet']));
        Assert::assertTrue($schema->isValid(['hexlet', 'code-basics']));
    }

    public function testCombinedValidators(): void
    {
        $schema = $this->validator->array()
            ->required()
            ->sizeof(2);

        Assert::assertFalse($schema->isValid(null));
        Assert::assertFalse($schema->isValid(['hexlet']));
        Assert::assertTrue($schema->isValid(['hexlet', 'code-basics']));
    }

    public function testShapeValidation(): void
    {
        $schema = $this->validator->array()->shape([
            'name' => $this->validator->string()->required(),
            'age' => $this->validator->number()->positive(),
        ]);

        // Valid cases
        Assert::assertTrue($schema->isValid(['name' => 'kolya', 'age' => 100]));
        Assert::assertTrue($schema->isValid(['name' => 'maya', 'age' => null]));
        Assert::assertTrue($schema->isValid(['name' => 'bob']));

        // Invalid cases
        Assert::assertFalse($schema->isValid(['name' => '', 'age' => null]));
        Assert::assertFalse($schema->isValid(['name' => 'ada', 'age' => -5]));
        Assert::assertFalse($schema->isValid(['age' => 10]));
    }

    public function testShapeWithNullable(): void
    {
        $schema = $this->validator->array()->shape([
                'profile' => $this->validator->array()->shape([
                'name' => $this->validator->string()->required()
                ])
        ]);

        Assert::assertTrue($schema->isValid(['profile' => ['name' => 'alice']]));
        Assert::assertTrue($schema->isValid(['profile' => null]));
        Assert::assertFalse($schema->isValid(['profile' => ['name' => '']]));
    }
}
