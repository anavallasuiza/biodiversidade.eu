<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api;

/**
 * Adapter executes an API request and returns the response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
interface Adapter
{
    /**
     * Executes the API request and returns the response.
     *
     * @param string $url The URL to execute.
     * 
     * @return string The raw API response.
     *
     * @throws \RuntimeException When an Exception occurs during execution.
     */
    public function executeRequest($url);
}