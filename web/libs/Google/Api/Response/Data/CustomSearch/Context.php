<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\CustomSearch;

use Google\Api\Response\Data\AbstractData;

/**
 * Context is a context from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Context extends AbstractData
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var array
     */
    protected $facets = array();
    
    /**
     * Gets the title of the custom search engine.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Gets the facets for the custom search engine.
     *
     * @return array
     * 
     * @link https://code.google.com/apis/customsearch/docs/refinements.html#create
     */
    public function getFacets()
    {
        return $this->facets;
    }
}