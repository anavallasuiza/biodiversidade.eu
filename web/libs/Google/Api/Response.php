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

use \Google\Api\Response\Data\AbstractData;
use \Google\Api\Response\Error;

/**
 * Response is a parsed and formatted Google API response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Response
{
    /**
     * @var AbstractData
     */
    protected $data;

    /**
     * @var Error
     */
    protected $error;

    /**
     * Constructs a new Response object.
     * 
     * @param AbstractData|Error $response Either the response data or error
     */
    public function __construct($response)
    {
        $this->setResponse($response);
    }

    /**
     * Sets the API response data or error.
     * 
     * @param AbstractData|Error $response
     *
     * @throws \LogicException When an unexpected response format is found.
     */
    protected function setResponse($response)
    {
        if($response instanceof AbstractData) {
            $this->data = $response;
        } else if($response instanceof Error) {
            $this->error = $response;
        } else {
            throw new \LogicException('Unexpected response format.');
        }
    }

    /**
     * Gets the response data.
     * 
     * @return AbstractData
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the response error.
     *
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Determines if the response was successful or not.
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getError() === null;
    }
}