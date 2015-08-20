<?php namespace Amelia\Money\Api\Adapter;

use Psr\Http\Message\RequestInterface;

/**
 * Generic adapter used for fetching rate data from arbitrary sources.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
interface AdapterInterface
{
    /**
     * Simple helper method to create a request for a GET and send it.
     *
     * @param string $url The URL to fetch
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetch($url);

    /**
     * Request directly using a PSR-7 request object.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(RequestInterface $request);
}
