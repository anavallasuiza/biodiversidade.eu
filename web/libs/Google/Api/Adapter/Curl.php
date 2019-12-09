<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Adapter;

use Google\Api\Adapter;

/**
 * Curl executes an API request using cURL and returns the response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Curl implements Adapter
{
    /**
     * {@inheritdoc}
     */
    public function executeRequest($url)
    {
        if(!function_exists('curl_init')) {
            // @codeCoverageIgnoreStart
            throw new Exception('cURL module not installed.');
            // @codeCoverageIgnoreEnd
        }

        $handle = @curl_init();
        if(!$handle) {
            // @codeCoverageIgnoreStart
            throw new Exception('Unable to create cURL session.');
            // @codeCoverageIgnoreEnd
        }

        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_URL, $url);

        $response = @curl_exec($handle);
        if(!$response) {
            throw new Exception('API request failed. curl_exec() returned FALSE.');
        }

        return $response;
    }
}