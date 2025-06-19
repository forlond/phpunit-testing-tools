<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\HttpClient;

use Forlond\TestTools\AbstractTestGroup;
use Forlond\TestTools\TestResettable;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestHttpClient extends AbstractTestGroup implements HttpClientInterface, TestResettable
{
    protected const GROUP_NAME = 'http client requests';

    /**
     * @var array<MockResponse>
     */
    private array $responses = [];

    private HttpClientInterface $delegate;

    public function __construct(
        callable|iterable|ResponseInterface|null $responseFactory = null,
        ?string                                  $baseUri = 'https://example.com',
    ) {
        $this->delegate = new MockHttpClient($responseFactory, $baseUri);
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = $this->delegate->request($method, $url, $options);

        $this->responses[] = $response;

        return $response;
    }

    public function stream(iterable|ResponseInterface $responses, ?float $timeout = null): ResponseStreamInterface
    {
        return $this->delegate->stream($responses, $timeout);
    }

    public function withOptions(array $options): static
    {
        $client = new self();
        $client->delegate = $this->delegate->withOptions($options);

        return $client;
    }

    public function expect(string $method, Constraint|\Stringable|string $uri): self
    {
        $this->next();
        $this->set('method', $method, static fn(MockResponse $response) => $response->getInfo('http_method'));
        $this->set('uri', $uri, static fn(MockResponse $response) => $response->getInfo('url'));

        return $this;
    }

    public function options(Constraint|array $options): self
    {
        $this->set('options', $options, static fn(MockResponse $response) => $response->getRequestOptions());

        return $this;
    }

    public function option(string $name, mixed $value): self
    {
        $this->set(
            sprintf('options.%s', $name),
            $value,
            static fn(MockResponse $response) => $response->getRequestOptions()[$name] ?? null
        );

        return $this;
    }

    public function reset(): void
    {
        $this->responses = [];
    }

    protected function getValue(): array
    {
        return $this->responses;
    }
}
