<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Security;

use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestPassport extends SelfValidatingPassport
{
    public function __construct(
        UserInterface|string $user = 'test_user',
        array                $roles = ['ROLE_TEST'],
        array                $badges = [],
    ) {
        if (is_string($user)) {
            $user = new InMemoryUser($user, null, $roles);
        }

        parent::__construct(new UserBadge($user->getUserIdentifier(), static fn() => $user), $badges);
    }
}
