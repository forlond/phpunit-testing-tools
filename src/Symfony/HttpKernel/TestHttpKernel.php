<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestHttpKernel implements HttpKernelInterface
{
    public ?Request $request = null;

    public ?Response $response = null;

    public int $type = HttpKernelInterface::MAIN_REQUEST;

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        return $this->response ?? new Response();
    }
}
