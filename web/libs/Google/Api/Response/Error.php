<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response;

/**
 * Error is a parsed and formatted errornous Google API response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Error
{
    /**
     * @var integer
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * Contructs a new Error object.
     * 
     * @param integer $code
     * @param string $message
     */
    public function __construct($code, $message)
    {
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * Gets the response error code.
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets the response error code.
     *
     * @param integer $code
     * 
     * @return Error
     */
    protected function setCode($code)
    {
        $this->code = $code !== null ? (int) $code : null;
        return $this;
    }

    /**
     * Gets the response error message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the response error message.
     *
     * @param string $message
     *
     * @return Error
     */
    protected function setMessage($message)
    {
        $this->message = $message !== null ? (string) $message : null;
        return $this;
    }
}