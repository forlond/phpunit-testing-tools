# Symfony/Security

## Integration

- Use the `TestSecuritySubscriberTrait` to create related security event instances.
- Use the `TestAuthenticator` to mock any `AuthenticatorInterface` instance.

## TestAuthenticator

The `TestAuthenticator` implements the `AuthenticatorInterface` interface.

This implementation allows to configure the following properties

- The `Request` instance.
- The `Passport` instance.
- The `TokenInterface` instance.
- The `Response` instance for a succeed authentication.
- The `Response` instance for a failed authentication.
- The firewall name

> [!NOTE]
> The `supports` method will always return true.

> [!NOTE]
> The `authenticate` method returns the configured Passport, otherwise it returns a `TestPassport` instance.

> [!NOTE]
> The `createToken` method returns the configured `TokenInterface`, otherwise it returns a `PostAuthenticationToken`
> instance.

> [!NOTE]
> The `onAuthenticationSuccess` method returns the configured succeed `Response` or null if the property was not
> configured.

> [!NOTE]
> The `onAuthenticationFailure` method returns the configured failed `Response` or null if the property was not
> configured.

```php
$authenticator = new TestAuthenticator();
$authenticator->request = new Request('/path');
$authenticator->passport = new TestPassport();
$authenticator->token = new NullToken();
$authenticator->firewall = 'main_firewall';
$authenticator->successResponse = new Response('OK');
$authenticator->failureResponse = new Response('KO');
```

## TestSecuritySubscriberTrait

```php
protected function createAuthenticationTokenCreatedEvent(?callable $configure): AuthenticationTokenCreatedEvent
```

Creates an `AuthenticationTokenCreatedEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `TokenInterface` and/or `Passport`.

```php
$event = $this->createAuthenticationTokenCreatedEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->passport = new TestPassport();
    $authenticator->token    = new NullToken();
});
```

---

```php
protected function createCheckPassportEvent(?callable $configure): CheckPassportEvent
```

Creates a `CheckPassportEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `Passport`.

```php
$event = $this->createCheckPassportEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->passport = new TestPassport();
});
```

---

```php
protected function createAuthenticationSuccessEvent(?callable $configure): AuthenticationSuccessEvent
```

Creates an `AuthenticationSuccessEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `TokenInterface`.

```php
$event = $this->createAuthenticationSuccessEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->token = new NullToken();
});
```

---

```php
protected function createInteractiveLoginEvent(?callable $configure): InteractiveLoginEvent
```

Creates an `InteractiveLoginEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `Request` and/or `TokenInterface`.

```php
$event = $this->createInteractiveLoginEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->request = new Request('/path');
    $authenticator->token   = new NullToken();
});
```

---

```php
protected function createLoginSuccessEvent(?callable $configure): LoginSuccessEvent
```

Creates a `LoginSuccessEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `Request`, `Passport`, `TokenInterface`,
succeed `Response` and/or a firewall name.

```php
$event = $this->createInteractiveLoginEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->request         = new Request('/path');
    $authenticator->passport        = new TestPassport();
    $authenticator->token           = new NullToken();
    $authenticator->firewall        = 'main_firewall';
    $authenticator->successResponse = new Response('OK');
});
```

---

```php
protected function createLoginFailureEvent(
    ?callable                $configure,
    ?AuthenticationException $exception = null,
): LoginFailureEvent
```

Creates a `LoginFailureEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `Request`, `Passport`, failed `Response` and/or
a firewall name.

Also, it is possible to pass any `AuthenticationException` exception. Otherwise, a basic `AuthenticationException`
instance is used.

```php
$event = $this->createLoginFailureEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->request         = new Request('/path');
    $authenticator->passport        = new TestPassport();
    $authenticator->firewall        = 'main_firewall';
    $authenticator->failureResponse = new Response('OK');
});
```

```php
$event = $this->createLoginFailureEvent(
    static function(TestAuthenticator $authenticator) {
        $authenticator->request         = new Request('/path');
        $authenticator->passport        = new TestPassport();
        $authenticator->firewall        = 'main_firewall';
        $authenticator->failureResponse = new Response('OK');
    },
    new AuthenticationException('Error')
);
```

---

```php
protected function createLogoutEvent(?callable $configure): LogoutEvent
```

Creates a `LogoutEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestAuthenticator` that can be updated with a custom `Request` and/or `TokenInterface`.

```php
$event = $this->createLogoutEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->request = new Request('/path');
    $authenticator->token   = new NullToken();
});
```

---

```php
protected function createSwitchUserEvent(?callable $configure): SwitchUserEvent
```

Creates a `SwitchUserEvent` object. The event can be configured by using the `$configure` closure.

> [!IMPORTANT]
> This event is for when the impersonation starts.

The closure gets a `TestAuthenticator` that can be updated with a custom `Request`, `TokenInterface` and/or user.

> [!NOTE]
> The `user` is provided by the configured `Passport`.

```php
$event = $this->createSwitchUserEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->request  = new Request('/path');
    $authenticator->token    = new NullToken();
    $authenticator->passport = new TestPassport();
});
```

---

```php
protected function createExitSwitchUserEvent(?callable $configure): SwitchUserEvent
```

Creates a `SwitchUserEvent` object. The event can be configured by using the `$configure` closure.

> [!IMPORTANT]
> This event is for when the impersonation ends.

The closure gets a `TestAuthenticator` that can be updated with a custom `Request` and/or `TokenInterface`.

```php
$event = $this->createExitSwitchUserEvent(static function(TestAuthenticator $authenticator) {
    $authenticator->request = new Request('/path');
    $authenticator->token   = new NullToken();
});
```
