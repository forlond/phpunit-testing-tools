This document details the changes that you need to make to your code when upgrading from one version to another.

Upgrading from 1.x to 2.x
=========================

## Doctrine

- The `doctrine/orm` compatible versions are `^2.10` and `^3.0`.
- The `TestDBALConnection::setException` allows to configure the `TestDBALDriver` to throw a `DriverException`.
- The `TestDBALDriver` constructor signature has changed. The `TestDBALDriverConnection` was dropped.
- The `TestDBALDriverConnection::query` returns a `TestResult` instead of a `ArrayResult` instance.
- The `TestStatement` constructor signature has changed. The first argument is a SQL string, the second argument is a
  results array.
- The `TestStatement::execute` returns a `TestResult` instead of a `ArrayResult` instance.
- The `AbstractEntityManagerTestCase::createConfiguration` will use `enableNativeLazyObjects` when available. Otherwise,
  it will configure the proxy directory and namespace.
- The `AbstractEventSubscriberTestCase` dropped all Doctrine event methods in favor of `TestORMEventsTrait`.
