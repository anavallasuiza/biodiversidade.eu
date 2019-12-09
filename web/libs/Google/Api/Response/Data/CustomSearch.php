<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data;

use Google\Api\Response\Data\CustomSearch\Context;

/**
 * CustomSearch contains the data from a Google Custom Search API response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class CustomSearch extends AbstractData
{
    /**
     * @var array
     */
    protected $queries = array();
    
    /**
     * @var array
     */
    protected $promotions = array();
    
    /**
     * @var Context
     */
    protected $context;
    
    /**
     * @var array
     */
    protected $items = array();
    
    /**
     * Determines if there are queries.
     * 
     * @return boolean
     */
    public function hasQueries()
    {
        return count($this->getQueries()) > 0;
    }

    /**
     * Gets the queries.
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
    
    /**
     * Determines if there are promotions.
     * 
     * @return boolean
     */
    public function hasPromotions()
    {
        return count($this->getPromotions()) > 0;
    }
    
    /**
     * Gets the promotions.
     *
     * @return array
     */
    public function getPromotions()
    {
        return $this->promotions;
    }
    
    /**
     * Determines if there are search results.
     * 
     * @return boolean
     */
    public function hasItems()
    {
        return count($this->getItems()) > 0;
    }
    
    /**
     * Gets the search results.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}