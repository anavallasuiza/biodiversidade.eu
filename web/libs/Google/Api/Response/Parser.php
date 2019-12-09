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

use Google\Api\Response;
use Google\Api\Response\Data\Parser as ResponseDataParser;
use Google\Api\Response\Error;

/**
 * Parser parses a raw Google API response and returns a formatted Response object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Parser
{
    /**
     * @var ResponseParser
     */
    protected $responseDataParser;

    /**
     * Constructs a new Parser object.
     * 
     * @param ResponseDataParser $responseDataParser The parser used to parse valid response data.
     */
    public function __construct(ResponseDataParser $responseDataParser)
    {
       $this->setResponseDataParser($responseDataParser);
    }

    /**
     * Sets the parser used to parse valid response data.
     * 
     * @param ResponseDataParser $responseDataParser
     *
     * @return Parser
     */
    protected function setResponseDataParser(ResponseDataParser $responseDataParser)
    {
        $this->responseDataParser = $responseDataParser;
        return $this;
    }

    /**
     * Gets the parser used to parse valid response data.
     *
     * @return ResponseDataParser
     */
    protected function getResponseDataParser()
    {
        return $this->responseDataParser;
    }

    /**
     * Parses a raw Google API response into a formatted Response object.
     *
     * @param string $apiResponse
     *
     * @return Response
     *
     * @throws \InvalidArgumentException When the API response is an invalid format.
     */
    public function parse($apiResponse)
    {
        if(!is_string($apiResponse)) {
            throw new \InvalidArgumentException('Invalid API response format. Expected non-empty string.');
        }

        $response = @json_decode($apiResponse);
        if(!($response instanceof \stdClass)) {
            throw new \InvalidArgumentException('The API response could not be JSON decoded.');
        }

        if(isset($response->error)) {
            
            if (!($response->error instanceof \stdClass)) {
                throw new \RuntimeException('Invalid API response error format.');
            }

            $responseObject = $this->parseError($response->error);
            
        } else {
            $responseObject = $this->getResponseDataParser()->parse($response);
        }

        return new Response($responseObject);
    }

    /**
     * Parses an errornous Google API response.
     *
     * @param \stdClass $error
     *
     * @return Error
     */
    protected function parseError(\stdClass $error)
    {
        return new Error(
            isset($error->code) ? $error->code : null,
            isset($error->message) ? $error->message : null
        );
    }
}