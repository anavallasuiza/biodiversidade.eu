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

use Google\Api\Adapter\Curl;
use Google\Api\Response\Parser as Parser;
use Google\Api\Response\Data\Parser as DataParser;

/**
 * AbstractApi is the base client class for the each Google API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * @link http://code.google.com/apis/language/translate/v2/using_rest.html
 */
abstract class AbstractApi
{
    /**
     * @var array
     */
    protected static $apiRequestCache = array();

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Constructs a new Google API client.
     *
     * @param Adapter $adapter The adapter used to make the API request.
     */
    public function __construct(Adapter $adapter = null)
    {
        $this->setAdapter($adapter ?: new Curl());
    }

    /**
     * Gets the adapter used to execute the API request.
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets the adapter used to execute the API request.
     *
     * @param Adapter $adapter
     * @return AbstractApi
     */
    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Executes the API request and returns a parsed and formatted Response object.
     *
     * @return Response
     */
    public function executeRequest()
    {
        $this->validateParameters();

        $parser = new Parser($this->getDataParser());
        return $parser->parse($this->executeApiRequest());
    }

    /**
     * Validates that all parameters are valid for for the API request.
     *
     * @return boolean
     *
     * @throws \LogicExcetion
     */
    abstract protected function validateParameters();

    /**
     * Gets the Data parser used by the response Parser.
     *
     * @return DataParser
     */
    abstract protected function getDataParser();

    /**
     * Executes the actual API request and returns the raw response.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    protected function executeApiRequest()
    {
        $requestUrl = $this->getApiRequestUrl();
        $cacheKey = md5($requestUrl);

        if(isset(static::$apiRequestCache[$cacheKey])) {
            return static::$apiRequestCache[$cacheKey];
        }

        return static::$apiRequestCache[$cacheKey] = $this->getAdapter()->executeRequest($requestUrl);
    }

    /**
     * Gets the URL used to make the API request.
     *
     * @return string
     */
    public function getApiRequestUrl()
    {
        $urlParameters = array();

        foreach ($this->getApiRequestData() as $key => $value) {
            if(($parameter = $this->generateApiRequestUrlPart($key, $value))) {
                array_push($urlParameters, $parameter);
            }
        }

        return $this->getApiUrl() . '?' . implode('&', $urlParameters);
    }

    /**
     * Gets the API request data.
     *
     * @return array
     */
    abstract protected function getApiRequestData();

    /**
     * Generates the specific part of the API request URL for the specified parameter and value.
     *
     * @param string $parameter
     * @param mixed $value
     *
     * @return string
     */
    protected function generateApiRequestUrlPart($parameter, $value = null)
    {
        switch(gettype($value)) {

            case 'NULL':
                return null;
                break;

            case 'array':

                $subParts = array();

                foreach($value as $subValue) {
                    array_push($subParts, $this->generateApiRequestUrlPart($parameter, $subValue));
                }

                return implode('&', $subParts);

                break;

            case 'boolean':
                return sprintf('%s=%s', $parameter, urlencode($value ? 'true' : 'false'));
                break;

            case 'float':
            case 'integer':
            case 'string':
                return sprintf('%s=%s', $parameter, urlencode($value));
                break;

            // @codeCoverageIgnoreStart
            default:
                throw new \LogicException('Unexpected parameter value data type.');
                break;
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Gets the endpoint URL for the Google API.
     *
     * @return string
     */
    abstract protected function getApiUrl();
}