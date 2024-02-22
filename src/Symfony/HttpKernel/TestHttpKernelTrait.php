<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Http\Event\LazyResponseEvent;

trait TestHttpKernelTrait
{
    private function createRequestEvent(?callable $configure): RequestEvent
    {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new RequestEvent($kernel, $kernel->request ?? Request::create(''), $kernel->type);
    }

    private function createLazyResponseEvent(RequestEvent $event): LazyResponseEvent
    {
        return new LazyResponseEvent($event);
    }

    private function createControllerEvent(?callable $configure, ?callable $controller = null): ControllerEvent
    {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new ControllerEvent(
            $kernel,
            $controller ?? static fn() => null,
            $kernel->request ?? Request::create(''),
            $kernel->type
        );
    }

    private function createControllerArgumentsEvent(
        array            $arguments,
        ?ControllerEvent $event = null,
    ): ControllerArgumentsEvent {
        $event ??= $this->createControllerEvent(null);

        return new ControllerArgumentsEvent(
            $event->getKernel(),
            $event,
            $arguments,
            $event->getRequest(),
            $event->getRequestType()
        );
    }

    private function createResponseEvent(?callable $configure): ResponseEvent
    {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new ResponseEvent(
            $kernel,
            $kernel->request ?? Request::create(''),
            $kernel->type,
            $kernel->response ?? new Response()
        );
    }

    private function createViewEvent(
        ?callable                 $configure,
        mixed                     $controllerResult = null,
        ?ControllerArgumentsEvent $event = null,
    ): ViewEvent {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new ViewEvent(
            $kernel,
            $kernel->request ?? Request::create(''),
            $kernel->type,
            $controllerResult,
            $event
        );
    }

    private function createFinishRequest(?callable $configure): FinishRequestEvent
    {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new FinishRequestEvent($kernel, $kernel->request ?? Request::create(''), $kernel->type);
    }

    private function createTerminateEvent(?callable $configure): TerminateEvent
    {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new TerminateEvent(
            $kernel,
            $kernel->request ?? Request::create(''),
            $kernel->response ?? new Response()
        );
    }

    private function createExceptionEvent(?callable $configure, ?\Throwable $exception = null): ExceptionEvent
    {
        $kernel = new TestHttpKernel();

        $configure && $configure($kernel);

        return new ExceptionEvent(
            $kernel,
            $kernel->request ?? Request::create(''),
            $kernel->type,
            $exception ?? new \Exception()
        );
    }
}
