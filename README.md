# PHPUnit Testing Tools

A set of utilities to facilitate the creation of unit tests for other libraries in PHPUnit.

Requires a PHP version `^8.1` and a PHPUnit version `^9.5 || ^10`

## Installation

```
composer require --dev forlond/phpunit-testing-tools
```

## Integrations

| Name                                                       | Supported Versions | Documentation                         |
|------------------------------------------------------------|--------------------|---------------------------------------|
| [JMS/Serializer](https://github.com/schmittjoh/serializer) | 3.x                | [Readme](./docs/jms_serializer.md)    |
| [Psr/Log](https://github.com/php-fig/log)                  | 1.x, 2.x, 3.x      | [Readme](./docs/psr_log.md)           |
| [Symfony/Form](https://github.com/symfony/form)            | 5.x, 6.x           | [Readme](./docs/symfony_form.md)      |
| [Symfony/Validator](https://github.com/symfony/validator)  | 5.x, 6.x           | [Readme](./docs/symfony_validator.md) |
