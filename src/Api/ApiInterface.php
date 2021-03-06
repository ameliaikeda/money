<?php

namespace Amelia\Money\Api;

use Carbon\Carbon;

/**
 * Repository for storing the responses from APIs, Mock data, etc
 * Implementations decide how to fetch data (files, database, rest api).
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
interface ApiInterface
{
    /**
     * Get latest rate data, usually associated with Carbon::now().
     *
     * @see \Carbon\Carbon::now()
     * @return \Amelia\Money\Container\RateContainerInterface
     */
    public function getLatest();

    /**
     * Get historical rate data (end-of-day) for $date.
     *
     * @param \Carbon\Carbon $date
     * @return \Amelia\Money\Container\RateContainerInterface
     */
    public function getHistorical(Carbon $date);

    /**
     * Get any updates that have happened since or for $date
     * This is implementation-specific (openexchangerates has no equivalent),
     * and is mostly used for HMRC's weekly updates in that implementation.
     *
     * @param \Carbon\Carbon $date
     * @return \Amelia\Money\Container\RateContainerInterface[]
     */
    public function getUpdates(Carbon $date);
}
