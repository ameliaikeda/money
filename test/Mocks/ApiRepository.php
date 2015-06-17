<?php namespace Amelia\Money\Mocks;

use Amelia\Money\ApiRepositoryInterface;
use Amelia\Money\Exception\AccessDeniedException;
use Amelia\Money\Exception\ApiException;
use Amelia\Money\Exception\BaseNotFoundException;
use Amelia\Money\Exception\InvalidAuthException;
use Amelia\Money\Exception\NotFoundException;
use Amelia\Money\Exception\RateLimitException;
use Carbon\Carbon;

/**
 * Mock Implementation of an API to serve content. Includes errors for testing.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class ApiRepository implements ApiRepositoryInterface {

    /**
     * Override response used for testing
     *
     * @var string
     */
    protected $error;

    /**
     * Get latest rate data, usually associated with Carbon::now()
     *
     * @see \Carbon\Carbon::now()
     * @return \Amelia\Money\Api\RateRepositoryInterface
     */
    public function getLatest()
    {
        $this->error();
        return $this->get("latest.json");
    }

    /**
     * Get historical rate data (end-of-day) for $date
     *
     * @param \Carbon\Carbon $date
     * @return \Amelia\Money\Api\RateRepositoryInterface
     */
    public function getHistorical(Carbon $date)
    {
        $this->error();
        return $this->get("historical/{$date->format("Y-m-d")}.json");
    }

    /**
     * Get any updates that have happened since or for $date
     * This is implementation-specific (openexchangerates has no equivalent),
     * and is mostly used for HMRC's weekly updates in that implementation
     *
     * @param \Carbon\Carbon $date
     * @return \Amelia\Money\Api\RateRepositoryInterface[]
     */
    public function getUpdates(Carbon $date)
    {
        throw new \BadMethodCallException("Getting mock updates is not implemented yet");
    }

    /**
     * Get json data from a file as an array
     *
     * @param string $filename
     * @return \Amelia\Money\Api\RateRepositoryInterface
     * @throws \Amelia\Money\Exception\NotFoundException
     */
    public function get($filename)
    {
        $file = "../data/$filename";

        if ( ! file_exists($file)) {
            throw new NotFoundException("$filename is not a valid test file", 404);
        }

        $data = json_decode(file_get_contents($file), true);

        return new RateRepository($data);
    }

    /**
     * Set an override response for all subsequent requests until reset
     *
     * @param string $error
     * @return void
     */
    public function setResponse($error)
    {
        $this->error = $error;
    }

    public function error()
    {
        if ( ! $this->error) {
            return;
        }

        $error = $this->get("errors/{$this->error}.json");

        switch ($this->error) {
            case "not-allowed":
            case "access-restricted":
                throw new AccessDeniedException($error["description"], $error["code"]);

            case "invalid-app-id":
            case "missing-app-id":
                throw new InvalidAuthException($error["description"], $error["code"]);

            case "invalid-base":
                throw new BaseNotFoundException($error["description"], $error["code"]);

            case "not-available":
            case "not-found":
                throw new NotFoundException($error["description"], $error["code"]);

            case "too-many-requests":
                throw new RateLimitException($error["description"], $error["code"]);

            case "null":
                throw new ApiException("An unknown error occurred", 500);

            default:
                break;
        }
    }

    /**
     * Reset the response override
     *
     * @return void
     */
    public function reset()
    {
        $this->response = null;
    }
}
