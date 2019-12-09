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
 * FileGetContents executes an API request using the file_get_contents() 
 * PHP method and returns the response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class FileGetContents implements Adapter
{
    /**
     * {@inheritdoc}
     */
    public function executeRequest($url)
    {
        $response = @file_get_contents($url);
        if(!$response) {
            throw new Exception('API request failed. file_get_contents() returned FALSE.');
        }

        return $response;
    }
}