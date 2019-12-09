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

use Google\Api\Response\Data\Parser\CustomSearch as DataParser;

/**
 * CustomSearch is the main client class for the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * @link https://code.google.com/apis/customsearch/v1/using_rest.html
 */
class CustomSearch extends AbstractApi
{
    const API_URL = 'https://www.googleapis.com/customsearch/v1';
    
    const PARAMETER_ALT = 'alt';
    const PARAMETER_API_KEY = 'key';
    const PARAMETER_CUSTOM_SEARCH_ENGINE_ID = 'cx';
    const PARAMETER_CUSTOM_SEARCH_ENGINE_SPEC_URL = 'cref';
    const PARAMETER_FILTER_DUPLICATES = 'filter';
    const PARAMETER_LANGUAGE_RESTRICTION = 'lr';
    const PARAMETER_NUMBER_OF_RESULTS = 'num';
    const PARAMETER_PRETTYPRINT = 'prettyprint';
    const PARAMETER_QUERY = 'q';
    const PARAMETER_SAFETY_LEVEL = 'safe';
    const PARAMETER_START_INDEX = 'start';
    
    const SAFETY_LEVEL_HIGH = 'high';
    const SAFETY_LEVEL_MEDIUM = 'medium';
    const SAFETY_LEVEL_OFF = 'off';
    
    const REGEX_LANGUAGE_RESTRICTION = "/^lang_(ar|bg|ca|cs|da|de|el|en|es|et|fi|fr|hr|hu|id|is|it|iw|ja|ko|lt|lv|nl|no|pl|pt|ro|ru|sk|sl|sr|sv|tr|zh\-CN|zh\-TW)$/";
    const REGEX_URL = "~^(http|https)://(([a-z0-9-]+\.)+[a-z]{2,6}|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(:[0-9]+)?(/?|/\S+)$~ix";

    /**
     * @var string
     */
    protected $apiKey;
    
    /**
     * @var string
     */
    protected $customSearchEngineId;
    
    /**
     * @var string
     */
    protected $customSearchEngineSpecUrl;
    
    /**
     * @var boolean
     */
    protected $filterDuplicates;
    
    /**
     * @var string
     */
    protected $languageRestriction;
    
    /**
     * @var integer
     */
    protected $numberOfResults = 10;
    
    /**
     * @var string
     */
    protected $query;
    
    /**
     * @var string
     */
    protected $safetyLevel;
    
    /**
     * @var integer
     */
    protected $startIndex;

    /**
     * Constructs a new Google CustomSearch API client.
     * 
     * @param string $query The search expression.
     * @param Adapter $adapter The adapter used to make the API request.
     */
    public function __construct($query = null, Adapter $adapter = null)
    {
        if($query !== null) {
            $this->setQuery($query);
        }

        parent::__construct($adapter);
    }

    /**
     * Gets the API key to used for the request.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the API key to used for the request.
     *
     * @param string $apiKey
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setApiKey($apiKey)
    {
        if(!(is_string($apiKey) && strlen(trim($apiKey)) > 0)) {
            throw new \InvalidArgumentException('Invalid API key. Please provide a non-empty string.');
        }

        $this->apiKey = $apiKey;
        return $this;
    }
    
    /**
     * Gets the custom search engine ID to scope this search to.
     *
     * @return string
     */
    public function getCustomSearchEngineId()
    {
        return $this->customSearchEngineId;
    }
    
    /**
     * Sets the custom search engine ID to scope this search to.
     *
     * @param string $customSearchEngineId
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setCustomSearchEngineId($customSearchEngineId = null)
    {
        if($customSearchEngineId !== null && !(is_string($customSearchEngineId) && strlen(trim($customSearchEngineId)) > 0)) {
            throw new \InvalidArgumentException(sprintf('Invalid custom search engine ID "%s". Please provide a non-empty string.', $customSearchEngineId));
        }
        
        $this->customSearchEngineId = $customSearchEngineId;
        return $this;
    }
    
    /**
     * Gets the custom search engine spec URL to scope this search to.
     *
     * @return string
     */
    public function getCustomSearchEngineSpecUrl()
    {
        return $this->customSearchEngineSpecUrl;
    }
    
    /**
     * Sets the custom search engine spec URL to scope this search to.
     *
     * @param string $customSearchEngineSpecUrl
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setCustomSearchEngineSpecUrl($customSearchEngineSpecUrl = null)
    {
        if($customSearchEngineSpecUrl !== null && !(is_string($customSearchEngineSpecUrl) && preg_match(self::REGEX_URL, $customSearchEngineSpecUrl))) {
            throw new \InvalidArgumentException(sprintf('Invalid custom search engine spec URL "%s". Please provide a valid URL.', $customSearchEngineSpecUrl));
        }
        
        $this->customSearchEngineSpecUrl = $customSearchEngineSpecUrl;
        return $this;
    }
    
    /**
     * Gets whether duplicates should be filtered or not.
     *
     * @return boolean
     */
    public function getFilterDuplicates()
    {
        return $this->filterDuplicates;
    }
    
    /**
     * Sets whether duplicates should be filtered or not.
     *
     * @param boolean $filterDuplicates
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setFilterDuplicates($filterDuplicates = null)
    {
        if($filterDuplicates !== null && !is_bool($filterDuplicates)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter duplicates value "%s". Please provide a boolean.', $filterDuplicates));
        }
        
        $this->filterDuplicates = $filterDuplicates;
        return $this;
    }
    
    /**
     * Gets the language to restrict search results to.
     *
     * @return string
     */
    public function getLanguageRestriction()
    {
        return $this->languageRestriction;
    }
    
    /**
     * Sets the language to restrict search results to.
     *
     * @param string $languageRestriction
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     * 
     * @link http://www.google.com/cse/docs/resultsxml.html#languageCollections
     */
    public function setLanguageRestriction($languageRestriction = null)
    {
        if($languageRestriction !== null && !(is_string($languageRestriction) && preg_match(self::REGEX_LANGUAGE_RESTRICTION, $languageRestriction))) {
            throw new \InvalidArgumentException(sprintf('Invalid language restriction "%s". Please see http://www.google.com/cse/docs/resultsxml.html#languageCollections for a list of valid language codes.', $languageRestriction));
        }
        
        $this->languageRestriction = $languageRestriction;
        return $this;
    }
    
    /**
     * Gets the number of results to return.
     *
     * @return integer
     */
    public function getNumberOfResults()
    {
        return $this->numberOfResults;
    }
    
    /**
     * Sets the number of results to return.
     *
     * @param integer $numberOfResults
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setNumberOfResults($numberOfResults = null)
    {
        if($numberOfResults !== null && !(is_int($numberOfResults) && $numberOfResults >= 1 && $numberOfResults <= 10)) {
            throw new \InvalidArgumentException(sprintf('Invalid number of results "%s". Please provide an integer between 1 and 10.', $numberOfResults));
        }
        
        $this->numberOfResults = $numberOfResults;
        return $this;
    }
    
    /**
     * Gets the search expression.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }
    
    /**
     * Sets the search expression.
     *
     * @param string $query
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setQuery($query)
    {
        if(!(is_string($query) && strlen(trim($query)) > 0)) {
            throw new \InvalidArgumentException(sprintf('Invalid query "%s". Please provide a non-empty string.', $query));
        }
        
        $this->query = $query;
        return $this;
    }
    
    /**
     * Gets the safety level.
     *
     * @return string
     */
    public function getSafetyLevel()
    {
        return $this->safetyLevel;
    }
    
    /**
     * Sets the safety level.
     *
     * @param string $safetyLevel
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     * 
     * @see SAFETY_LEVEL_HIGH, SAFETY_LEVEL_MEDIUM, SAFETY_LEVEL_OFF
     */
    public function setSafetyLevel($safetyLevel = null)
    {
        if($safetyLevel !== null && !(!is_bool($safetyLevel) && in_array($safetyLevel, array(self::SAFETY_LEVEL_HIGH, self::SAFETY_LEVEL_MEDIUM, self::SAFETY_LEVEL_OFF)))) {
            throw new \InvalidArgumentException(sprintf('Invalid safety level "%s". Please provide either "high", "medium" or "off".', $safetyLevel));
        }
        
        $this->safetyLevel = $safetyLevel;
        return $this;
    }
    
    /**
     * Gets the start index.
     *
     * @return integer
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }
    
    /**
     * Sets the start index.
     *
     * @param integer $startIndex
     *
     * @return CustomSearch
     *
     * @throws \InvalidArgumentException
     */
    public function setStartIndex($startIndex = null)
    {
        if($startIndex !== null && !(is_int($startIndex) && $startIndex >= 1 && $startIndex <= (101 - $this->getNumberOfResults()))) {
            throw new \InvalidArgumentException(sprintf('Invalid start index "%s". Please provide an integer between 1 and (101 - number of results).', $startIndex));
        }
        
        $this->startIndex = $startIndex;
        return $this;
    }

    /**
     * Validates that all parameters are valid for for the API request.
     * 
     * @return boolean
     *
     * @throws \LogicExcetion
     */
    protected function validateParameters()
    {
        if($this->getApiKey() === null) {
            throw new \RuntimeException('Missing required parameter "API key".');
        }
        
        if ($this->getCustomSearchEngineId() === null && $this->getCustomSearchEngineSpecUrl() === null) {
            throw new \RuntimeException('Missing required parameter customer search engine ID or spec URL (only one is required).');
        }

        if($this->getQuery() === null) {
            throw new \RuntimeException('Missing required parameter "query".');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDataParser()
    {
        return new DataParser();
    }

    /**
     * Gets the API request data.
     * 
     * @return array
     */
    protected function getApiRequestData()
    {
        $data = array(
            self::PARAMETER_ALT                           => 'json',
            self::PARAMETER_PRETTYPRINT                   => false,
            self::PARAMETER_CUSTOM_SEARCH_ENGINE_SPEC_URL => $this->getCustomSearchEngineSpecUrl(),
            self::PARAMETER_CUSTOM_SEARCH_ENGINE_ID       => $this->getCustomSearchEngineId(),
            self::PARAMETER_API_KEY                       => $this->getApiKey(),
            self::PARAMETER_LANGUAGE_RESTRICTION          => $this->getLanguageRestriction(),
            self::PARAMETER_NUMBER_OF_RESULTS             => $this->getNumberOfResults(),
            self::PARAMETER_QUERY                         => $this->getQuery(),
            self::PARAMETER_SAFETY_LEVEL                  => $this->getSafetyLevel(),
            self::PARAMETER_START_INDEX                   => $this->getStartIndex(),
            self::PARAMETER_FILTER_DUPLICATES             => $this->getFilterDuplicates(),
        );
        
        if($this->getCustomSearchEngineId() !== null) {
            unset($data[self::PARAMETER_CUSTOM_SEARCH_ENGINE_SPEC_URL]);
        }
        
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiUrl() {
        return self::API_URL;
    }
}