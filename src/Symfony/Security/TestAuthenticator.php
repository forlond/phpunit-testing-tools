<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestAuthenticator extends AbstractAuthenticator
{
    public ?Request $request = null;

    public ?Passport $passport = null;

    public ?TokenInterface $token = null;

    public string $firewall = 'firewall';

    public ?Response $successResponse = null;

    public ?Response $failureResponse = null;

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        return $this->passport ?? new TestPassport();
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return $this->token ?? parent::createToken($passport, $firewallName);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->successResponse;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->failureResponse;
    }
}
