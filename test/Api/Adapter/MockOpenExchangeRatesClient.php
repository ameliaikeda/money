<?php

namespace Amelia\Money\Api\Adapter;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * A mock API that uses static data taken from the OpenExchangeRates API.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class MockOpenExchangeRatesClient implements AdapterInterface
{
    /**
     * Overridden URL for tests.
     *
     * @var string
     */
    protected $url;

    /**
     * If we send json or not.
     *
     * @var bool
     */
    protected $bad = false;

    /**
     * Test-specific helper for testing our array responses.
     *
     * @param string $url
     */
    public function setEndpoint($url)
    {
        $this->url = $url;
    }

    /**
     * Get the filename of our sample data.
     *
     * @param string $url
     * @return string
     */
    protected function getEndpoint($url)
    {
        return __DIR__ . '/data' . ($this->url ?: $url);
    }

    /**
     * Simple helper method to create a request for a GET and send it.
     *
     * @param string $url The URL to fetch
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetch($url)
    {
        $body = file_get_contents($this->getEndpoint($url));
        $headers = ['Content-Type' => ($this->bad) ? 'text/html' : 'application/json'];

        return new Response(
            array_get(json_decode($body, true), 'status', 200), // status code will override whatever is sent
            $headers,
            $body
        );
    }

    /**
     * Request directly using a PSR-7 request object.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(RequestInterface $request)
    {
        return;
    }

    /**
     * Do we return a malformed json response?
     *
     * @param bool $bad
     */
    public function setBadRequest($bad)
    {
        $this->bad = $bad;
    }
}
