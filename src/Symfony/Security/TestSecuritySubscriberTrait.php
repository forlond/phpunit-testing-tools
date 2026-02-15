<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
trait TestSecuritySubscriberTrait
{
    protected function createAuthenticationTokenCreatedEvent(?\Closure $configure): AuthenticationTokenCreatedEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new AuthenticationTokenCreatedEvent($token, $passport);
    }

    protected function createCheckPassportEvent(?\Closure $configure): CheckPassportEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);

        return new CheckPassportEvent($authenticator, $passport);
    }

    protected function createAuthenticationSuccessEvent(?\Closure $configure): AuthenticationSuccessEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new AuthenticationSuccessEvent($token);
    }

    protected function createInteractiveLoginEvent(?\Closure $configure): InteractiveLoginEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new InteractiveLoginEvent($request, $token);
    }

    protected function createLoginSuccessEvent(?\Closure $configure): LoginSuccessEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new LoginSuccessEvent(
            $authenticator,
            $passport,
            $token,
            $request,
            $authenticator->successResponse,
            $authenticator->firewall
        );
    }

    protected function createLoginFailureEvent(
        ?\Closure                $configure,
        ?AuthenticationException $exception = null,
    ): LoginFailureEvent {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        if ($exception instanceof AccountStatusException) {
            $exception->setUser($passport->getUser());
            $exception->setToken($authenticator->createToken($passport, $authenticator->firewall));
        }

        return new LoginFailureEvent(
            $exception ?? new AuthenticationException(),
            $authenticator,
            $request,
            $authenticator->failureResponse,
            $authenticator->firewall,
            $passport
        );
    }

    protected function createLogoutEvent(?\Closure $configure): LogoutEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new LogoutEvent($request, $token);
    }

    protected function createSwitchUserEvent(?\Closure $configure): SwitchUserEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request      = $authenticator->request ?? Request::create('');
        $passport     = $authenticator->authenticate($request);
        $impersonator = new InMemoryUser('impersonator', null);
        $user         = $passport->getUser();
        $token        = new PostAuthenticationToken($impersonator, $authenticator->firewall, []);
        $token        = new SwitchUserToken($user, $authenticator->firewall, ['ROLE_PREVIOUS_ADMIN'], $token);

        return new SwitchUserEvent($request, $user, $token);
    }

    protected function createExitSwitchUserEvent(?\Closure $configure): SwitchUserEvent
    {
        $authenticator = new TestAuthenticator();

        if (null !== $configure) {
            $configure($authenticator);
        }

        $request      = $authenticator->request ?? Request::create('');
        $impersonator = new InMemoryUser('impersonator', null);
        $token        = new PostAuthenticationToken($impersonator, $authenticator->firewall, []);

        return new SwitchUserEvent($request, $impersonator, $token);
    }
}
