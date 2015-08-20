<?php namespace Amelia\Money\Api\Adapter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Guzzle adapter.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class GuzzleHttpAdapter implements AdapterInterface
{
    /**
     * Guzzle client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * Inject a guzzle client into the adapter.
     *
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Simple helper method to create a request for a GET and send it.
     *
     * @param string $url The URL to fetch
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetch($url)
    {
        $request = $this->createRequest($url, 'GET');

        return $this->request($request);
    }

    /**
     * Request directly using a PSR-7 request object.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(RequestInterface $request)
    {
        return $this->client->send($request);
    }

    /**
     * Create a simple PSR-7 request object from a URL and a method.
     *
     * @param string $url
     * @param string $method
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function createRequest($url, $method)
    {
        return new Request($method, $url);
    }
}
