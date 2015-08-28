<?php namespace Amelia\Money\Api;

use Amelia\Money\Api\Adapter\AdapterInterface;
use Amelia\Money\Container\OpenExchangeRateContainer;
use Amelia\Money\Exception\AccessDeniedException;
use Amelia\Money\Exception\BaseNotFoundException;
use Amelia\Money\Exception\InvalidAuthException;
use Amelia\Money\Exception\InvalidResponseException;
use Amelia\Money\Exception\NotFoundException;
use Amelia\Money\Exception\RateLimitException;
use Carbon\Carbon;
use Psr\Http\Message\ResponseInterface;

/**
 * An API client used for fetching exchange rates.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 * @link https://openexchangerates.org
 */
class OpenExchangeRates implements ApiInterface
{
    /**
     * HTTP transport adapter.
     *
     * @var \Amelia\Money\Api\Adapter\AdapterInterface
     */
    protected $client;

    /**
     * Construct a new instance with an adapter.
     *
     * @param \Amelia\Money\Api\Adapter\AdapterInterface $client
     */
    public function __construct(AdapterInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Check the response that was sent off and return it.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Amelia\Money\Exception\InvalidResponseException
     * @return \stdClass
     */
    protected function check(ResponseInterface $response)
    {
        if (! $this->isJson($response)) {
            $header = array_get($response->getHeader('content-type'), 0, "no content-type");
            throw new InvalidResponseException("json was expected, [$header] given");
        }
        $response = json_decode($response->getBody()->getContents());

        if (! $response) {
            throw new InvalidResponseException('Expected json object, got '.gettype($response).' from the API');
        }

        if (isset($response->error)) {
            return $this->error($response->status, $response->message, $response->description);
        }

        return $response;
    }

    /**
     * Throw exceptions that correspond to error codes.
     *
     * @param int $status
     * @param string $code
     * @param string $description
     *
     * @throws \Amelia\Money\Exception\AccessDeniedException
     * @throws \Amelia\Money\Exception\BaseNotFoundException
     * @throws \Amelia\Money\Exception\InvalidAuthException
     * @throws \Amelia\Money\Exception\InvalidResponseException
     * @throws \Amelia\Money\Exception\NotFoundException
     * @throws \Amelia\Money\Exception\RateLimitException
     */
    protected function error($status, $code, $description)
    {
        // teapots require special handling
        if ($status === 429) {
            throw new RateLimitException($description, $status);
        }

        switch ($code) {
            case 'access_restricted':
            case 'not_allowed':
                throw new AccessDeniedException($description, $status);

            case 'invalid_base':
                throw new BaseNotFoundException($description, $status);

            case 'missing_app_id':
            case 'invalid_app_id':
                throw new InvalidAuthException($description, $status);

            case 'not_available':
            case 'not_found':
                throw new NotFoundException($description, $status);

            default:
                throw new InvalidResponseException($description." ($code)", $status);
        }
    }

    /**
     * Check if a response is a valid json response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return bool
     */
    protected function isJson(ResponseInterface $response)
    {
        $type = array_get($response->getHeader('content-type'), 0, '');

        return strpos($type, 'json') !== false;
    }

    /**
     * Get latest rate data, usually associated with Carbon::now().
     *
     * @see \Carbon\Carbon::now()
     * @return \Amelia\Money\Container\RateContainerInterface
     */
    public function getLatest()
    {
        $response = $this->client->fetch('/latest.json');
        $response = $this->check($response);

        return new OpenExchangeRateContainer($response);
    }

    /**
     * Get historical rate data (end-of-day) for $date.
     *
     * @param \Carbon\Carbon $date
     * @return \Amelia\Money\Container\RateContainerInterface
     */
    public function getHistorical(Carbon $date)
    {
        $response = $this->client->fetch("/historical/{$date->format('Y-m-d')}.json");
        $response = $this->check($response);

        return new OpenExchangeRateContainer($response);
    }

    /**
     * Get any updates that have happened since or for $date
     * This is implementation-specific (openexchangerates has no equivalent),
     * and is mostly used for HMRC's weekly updates in that implementation.
     *
     * @param \Carbon\Carbon $date
     * @return \Amelia\Money\Container\RateContainerInterface[]
     *
     * @throws \Amelia\Money\Exception\NotFoundException
     */
    public function getUpdates(Carbon $date)
    {
        throw new NotFoundException('OpenExchangeRates has no "updates" analogue, use getLatest or getHistorical instead');
    }
}
