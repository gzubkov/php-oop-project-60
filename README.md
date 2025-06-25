### Hexlet tests and linter status:
[![Actions Status](https://github.com/gzubkov/php-oop-project-60/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/gzubkov/php-oop-project-60/actions)
[![PHP CI](https://github.com/hexlet-components/slim-example/actions/workflows/phpci.yml/badge.svg)](https://github.com/hexlet-components/slim-example/actions/workflows/phpci.yml)

Данный учебный проект реализует валидатор данных. [Описание задачи](https://ru.hexlet.io/programs/php-oop/projects/60).

## Использование
### Начало работы
Для начала работы необходимо создать объект класса `Validator`.
```php
$v = new \Hexlet\Validator\Validator();
```
Для удобства (и выполнения условий задания) реализован fluent interface, который позволяет комбинировать критерии проверки.
Проверка производится сперва указанием типа для валидации, затем возможен вызов отдельных функций, определяющих критерии проверки, после чего вызывается функция `isValid($data)`, передающая данные для проверки.
```php
$schema = $v->string()->minLength(5)->contains('git')->isValid('Welcome to git!'); // true
```
Сначала вызывается метод, определяющий тип данных:

`string()` - для строковых данных; 

`number()` - для числовых данных; 

`array()` - для массивов; 

Далее определяются критерии валидации. 
Последним вызывается метод `isValid(mixed $value)`, который проверяет соответствие переданного значения критериям валидации.

> [!NOTE]
> При повторном обращении к валидатору создается новая "схема валидации", что позволяет не создавать объект `Validator` множество раз.

## Требования к проверке (критерии)
### Проверка "является ли значение обязательным?"
Является общим для всех типов данных. Выполняется с помощью вызова метода `required()`.

### Проверка строковых данных
`contains($substring)` - устанавливает критерий необходимости наличия подстроки `$substring` в проверяемой строке

`minLength($length)` - устанавливает критерий минимальной длины проверяемой строки

```php
$schema = $v->string();
$schema->contains('php')->isValid('I love php'); // true
$schema->contains('ruby')->isValid('I love php'); // false

$v->string()->minLength(5)->isValid('Hexlet'); // true
```

### Проверка числовых данных
`positive()` - устанавливает критерий положительности числа.

`range($min, $max)` - устанавливает критерий вхождения числа в множество `[$min, $max]`.

```php
$schema = $v->number();
$schema->isValid(null); // true
$schema->required();
$schema->isValid(null); // false
$schema->isValid(0); // true
$schema->positive()->isValid(1); // true
$schema->range(-10, 10);
$schema->isValid(5); // true
$schema->isValid(-3); // false - число входит в диапазон, но не является положительным
```

### Проверка массивов

> [!WARNING]
> При требовании обязательности значения пустой массив тоже будет валиден.
> ```php
>$schema = $v->array()->required();
>$schema->isValid(null); // false
>$schema->isValid([]); // true
> ```

`sizeOf($num)` - устанавливает критерий соответствия длины массива (`$num`)

`shape()` - устанавливает критерии валидации значений массива по ключу (вызываются только для соответствующих типов данных)

```php
$schema = $v->array();
$schema->isValid(['test']); // true

$schema->sizeof(2);
$schema->isValid(['test']); // false
$schema->isValid(['first value', 'second value']); // true

$schema->shape([
    'name' => $v->string()->required(),
    'age' => $v->number()->positive(),
]);

$schema->isValid(['name' => 'kolya', 'age' => 100]); // true
$schema->isValid(['name' => 'maya', 'age' => null]); // true
$schema->isValid(['name' => '', 'age' => null]); // false
$schema->isValid(['name' => 'ada', 'age' => -5]); // false
```

## Использование функций для валидации

Для валидации данных возможно использовать сторонние функции, возвращающие тип `bool`

> [!NOTE]
> Функция добавляется определяется для каждого типа данных отдельно.

`addValidator(string $type, string $functionName, callable $fn)` - добавляет функцию `$fn` для проверки данных типа `$type` с псевдонином `$functionName`.

`test(string $functionName, ...$args)` - вызывает функцию с псевдонином `$functionName` и указанными аргументами, в случае возврата `true` данные считаются валидными.

```php
$v = new \Hexlet\Validator\Validator();

$fn = fn($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn);

$schema = $v->string()->test('startWith', 'G');
$schema->isValid('bitbucket'); // false
$schema->isValid('Github'); // true

$fn = fn($value, $min) => $value >= $min;
$v->addValidator('number', 'min', $fn);

$schema = $v->number()->test('min', 5);
$schema->isValid(4); // false
$schema->isValid(6); // true
```
