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
use Google\Api\Response\Data\CustomSearch\Item\PageMap;

/**
 * Item is a single item from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Item extends AbstractData
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $htmlTitle;
    
    /**
     * @var string
     */
    protected $link;
    
    /**
     * @var string
     */
    protected $displayLink;
    
    /**
     * @var string
     */
    protected $snippet;
    
    /**
     * @var string
     */
    protected $htmlSnippet;
    
    /**
     * @var string
     */
    protected $cacheId;
    
    /**
     * @var array
     */
    protected $pagemap = array();
    
    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Gets the title in HTML.
     *
     * @return string
     */
    public function getHtmlTitle()
    {
        return $this->htmlTitle;
    }
    
    /**
     * Gets the full URL.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * Gets the abridged version's link.
     *
     * @return string
     */
    public function getDisplayLink()
    {
        return $this->displayLink;
    }
    
    /**
     * Gets the snippet.
     *
     * @return string
     */
    public function getSnippet()
    {
        return $this->snippet;
    }
    
    /**
     * Gets the snippet in HTML.
     *
     * @return string
     */
    public function getHtmlSnippet()
    {
        return $this->htmlSnippet;
    }
    
    /**
     * Gets the cache ID.
     *
     * @return string
     */
    public function getCacheId()
    {
        return $this->cacheId;
    }
    
    /**
     * Gets the PageMaps.
     * 
     * @return array 
     */
    public function getPageMaps()
    {
        return $this->pagemap;
    }
    
    /**
     * Gets a specific PageMap by name.
     * 
     * @param string $name 
     * 
     * @return PageMap
     */
    public function getPageMap($name)
    {
        if(isset($this->pagemap[$name])) {
            return $this->pagemap[$name];
        }

        return null;
    }
}