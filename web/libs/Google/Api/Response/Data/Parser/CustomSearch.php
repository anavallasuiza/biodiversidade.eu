<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Parser;

use Google\Api\Response\Data\CustomSearch as CustomSearchData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\CustomSearch\Context as ContextParser;
use Google\Api\Response\Data\Parser\CustomSearch\Item as ItemParser;
use Google\Api\Response\Data\Parser\CustomSearch\Promotion as PromotionParser;
use Google\Api\Response\Data\Parser\CustomSearch\Query as QueryParser;
use Google\Api\Response\Data\Parser\Exception;


/**
 * CustomSearch parses raw data into a formatted CustomSearch Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class CustomSearch implements Parser
{
    const KIND = 'customsearch#search';
    
    /**
     * Parses raw data into a formatted CustomSearch Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return CustomSearchData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->kind) && $data->kind === self::KIND)) {
            throw new Exception(sprintf('Missing/invalid response kind. Expected "%s".', self::KIND));
        }
        
        if(!(isset($data->queries) && $data->queries instanceof \stdClass)) {
            throw new Exception('Missing/invalid queries data.');
        }
        
        $formattedData['queries'] = $this->parseQueries($data->queries);
        
        if(isset($data->promotions)) {
            
            if(!is_array($data->promotions)) {
                throw new Exception('Invalid promotions data.');
            }
            
            $formattedData['promotions'] = $this->parsePromotions($data->promotions);
        }
        
        if(isset($data->context)) {

            if(!($data->context instanceof \stdClass)) {
                throw new Exception('Invalid context data.');
            }
            
            $formattedData['context'] = $this->parseContext($data->context);
        }
        
        if(isset($data->items)) {

            if(!is_array($data->items)) {
                throw new Exception('Invalid items data.');
            }
            
            $formattedData['items'] = $this->parseItems($data->items);
        }
        
        return new CustomSearchData($formattedData);
    }

    /**
     * Gets the Query parser used by this parser.
     * 
     * @return QueryParser
     */
    protected function getQueryParser()
    {
        return new QueryParser();
    }
    
    /**
     * Parses the "queries" part of the data.
     *
     * @param \stdClass $queries
     *
     * @return array
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parseQueries(\stdClass $queries)
    {
        $queryObjects = array();
        $queryParser = $this->getQueryParser();

        foreach($queries as $type => $query) {
            
            if(!(is_array($query) && count($query) === 1 && array_key_exists(0, $query) && $query[0] instanceof \stdClass)) {
                throw new Exception('Invalid query format.');
            }
            
            $queryObjects[$type] = $queryParser->parse($query[0]);
        }
        
        return $queryObjects;
    }

    /**
     * Gets the Promotion parser used by this parser.
     *
     * @return PromotionParser
     */
    protected function getPromotionParser()
    {
        return new PromotionParser();
    }
    
    /**
     * Parses the "promotions" part of the data.
     *
     * @param \stdClass $promotions
     *
     * @return array
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parsePromotions(array $promotions)
    {
        $promotionObjects = array();
        $promotionParser = $this->getPromotionParser();

        foreach($promotions as $type => $promotion) {
            
            if(!($promotion instanceof \stdClass)) {
                throw new Exception('Invalid promotion format.');
            }
            
            array_push($promotionObjects, $promotionParser->parse($promotion));
        }
        
        return $promotionObjects;
    }

    /**
     * Gets the Context parser used by this parser.
     *
     * @return ContextParser
     */
    protected function getContextParser()
    {
        return new ContextParser();
    }
    
    /**
     * Parses the "context" part of the data.
     *
     * @param \stdClass $context
     *
     * @return ContextData
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parseContext(\stdClass $context)
    {
        return $this->getContextParser()->parse($context);
    }

    /**
     * Gets the Item parser used by this parser.
     *
     * @return ItemParser
     */
    protected function getItemParser()
    {
        return new ItemParser();
    }
    
    /**
     * Parses the "items" part of the data.
     *
     * @param \stdClass $items
     *
     * @return array
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parseItems(array $items)
    {
        $itemObjects = array();
        $itemParser = $this->getItemParser();

        foreach($items as $item) {
            
            if(!($item instanceof \stdClass)) {
                throw new Exception('Invalid item format.');
            }
            
            array_push($itemObjects, $itemParser->parse($item));
        }
        
        return $itemObjects;
    }
}