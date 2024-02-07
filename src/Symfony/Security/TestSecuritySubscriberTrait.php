<?php declare(strict_types=1);

namespace Tests\Tools\Symfony\Security;

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
    private function createAuthenticationTokenCreatedEvent(?callable $configure): AuthenticationTokenCreatedEvent
    {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new AuthenticationTokenCreatedEvent($token, $passport);
    }

    private function createCheckPassportEvent(?callable $configure): CheckPassportEvent
    {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);

        return new CheckPassportEvent($authenticator, $passport);
    }

    private function createAuthenticationSuccessEvent(?callable $configure): AuthenticationSuccessEvent
    {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new AuthenticationSuccessEvent($token);
    }

    private function createInteractiveLoginEvent(?callable $configure): InteractiveLoginEvent
    {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new InteractiveLoginEvent($request, $token);
    }

    private function createLoginSuccessEvent(?callable $configure): LoginSuccessEvent
    {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

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

    private function createLoginFailureEvent(
        ?callable                $configure,
        ?AuthenticationException $exception = null,
    ): LoginFailureEvent {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

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

    private function createLogoutEvent(?callable $configure): LogoutEvent
    {
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $request  = $authenticator->request ?? Request::create('');
        $passport = $authenticator->authenticate($request);
        $token    = $authenticator->createToken($passport, $authenticator->firewall);

        return new LogoutEvent($request, $token);
    }

    private function createSwitchUserEvent(?callable $configure): SwitchUserEvent
    {
        $request       = Request::create('');
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $passport     = $authenticator->authenticate($request);
        $impersonator = new InMemoryUser('impersonator', null);
        $user         = $passport->getUser();
        $token        = new PostAuthenticationToken($impersonator, $authenticator->firewall, []);
        $token        = new SwitchUserToken($user, $authenticator->firewall, ['ROLE_PREVIOUS_ADMIN'], $token);

        return new SwitchUserEvent($request, $user, $token);
    }

    private function createExitSwitchUserEvent(?callable $configure): SwitchUserEvent
    {
        $request       = Request::create('');
        $authenticator = new TestAuthenticator();

        $configure && $configure($authenticator);

        $impersonator = new InMemoryUser('impersonator', null);
        $token        = new PostAuthenticationToken($impersonator, $authenticator->firewall, []);

        return new SwitchUserEvent($request, $impersonator, $token);
    }
}
