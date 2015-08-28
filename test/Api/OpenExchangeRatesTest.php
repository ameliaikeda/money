<?php namespace Amelia\Money\Api;

use Amelia\Money\Api\Adapter\MockOpenExchangeRatesClient;
use Carbon\Carbon;
use PHPUnit_Framework_TestCase;

/**
 * Test that the openexchangerates API wrapper works correctly using a mock API
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class OpenExchangeRatesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Amelia\Money\Api\Adapter\MockOpenExchangeRatesClient
     */
    protected $client;

    /**
     * Set up tests
     */
    public function setUp() {
        $this->client = new MockOpenExchangeRatesClient();
    }

    public function testInitializesCorrectly()
    {
        $api = new OpenExchangeRates($this->client);
        $this->assertInstanceOf('Amelia\Money\Api\OpenExchangeRates', $api);
    }

    public function testFetchLatest()
    {
        $api = new OpenExchangeRates($this->client);
        $latest = $api->getLatest();

        $this->assertInstanceOf('Amelia\Money\Container\RateContainerInterface', $latest);
    }

    /**
     * @expectedException \Amelia\Money\Exception\InvalidResponseException
     * @expectedExceptionMessage json was expected
     */
    public function testFetchLatestBadRequest()
    {
        $this->client->setBadRequest(true);
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\InvalidResponseException
     * @expectedExceptionMessage NULL
     */
    public function testNullResponse()
    {
        $this->client->setEndpoint('/errors/null.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\AccessDeniedException
     * @expectedExceptionMessage access has been revoked
     * @expectedExceptionCode 403
     */
    public function testAccessRestrictedError()
    {
        $this->client->setEndpoint('/errors/access-restricted.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\AccessDeniedException
     * @expectedExceptionMessage only available for Enterprise and Unlimited clients
     * @expectedExceptionCode 403
     */
    public function testNotAllowedError()
    {
        $this->client->setEndpoint('/errors/not-allowed.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\InvalidAuthException
     * @expectedExceptionMessage Invalid App ID
     * @expectedExceptionCode 400
     */
    public function testInvalidAppIdError()
    {
        $this->client->setEndpoint('/errors/invalid-app-id.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\InvalidAuthException
     * @expectedExceptionMessage Missing App ID
     * @expectedExceptionCode 400
     */
    public function testMissingAppIdError()
    {
        $this->client->setEndpoint('/errors/missing-app-id.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\BaseNotFoundException
     * @expectedExceptionMessage unsupported `base` currency
     * @expectedExceptionCode 400
     */
    public function testInvalidBaseError()
    {
        $this->client->setEndpoint('/errors/invalid-base.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\NotFoundException
     * @expectedExceptionMessage Historical rates for the requested date are not available
     * @expectedExceptionCode 400
     */
    public function testNotAvailableError()
    {
        $this->client->setEndpoint('/errors/not-available.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\NotFoundException
     * @expectedExceptionCode 404
     */
    public function testNotFoundError()
    {
        $this->client->setEndpoint('/errors/not-found.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\RateLimitException
     * @expectedExceptionMessage Too Many Requests from your App Key
     * @expectedExceptionCode 429
     */
    public function testTooManyRequestsError()
    {
        $this->client->setEndpoint('/errors/too-many-requests.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    /**
     * @expectedException \Amelia\Money\Exception\InvalidResponseException
     * @expectedExceptionMessage an internal error occurred (internal_error)
     * @expectedExceptionCode 500
     */
    public function testGenericError()
    {
        $this->client->setEndpoint('/errors/generic.json');
        $api = new OpenExchangeRates($this->client);
        $api->getLatest();
    }

    public function testGetHistoricalRates()
    {
        $api = new OpenExchangeRates($this->client);
        $rates = $api->getHistorical(Carbon::parse('2011-10-18'));

        $this->assertInstanceOf('Amelia\Money\Container\RateContainerInterface', $rates);
    }

    /**
     * @expectedException \Amelia\Money\Exception\NotFoundException
     * @expectedExceptionMessage Historical rates for the requested date are not available
     * @expectedExceptionCode 400
     */
    public function testGetOldHistoricalRates()
    {
        $api = new OpenExchangeRates($this->client);
        $api->getHistorical(Carbon::parse('1998-01-22'));
    }

    /**
     * We don't have any way to fetch updates from a certain point
     *
     * @expectedException \Amelia\Money\Exception\NotFoundException
     */
    public function testGetUpdates()
    {
        $api = new OpenExchangeRates($this->client);
        $api->getUpdates(Carbon::now());
    }





}
