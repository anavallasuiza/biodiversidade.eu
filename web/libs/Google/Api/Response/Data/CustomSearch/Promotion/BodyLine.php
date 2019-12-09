<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\CustomSearch\Promotion;

use Google\Api\Response\Data\AbstractData;

/**
 * BodyLine is a single promotion block object from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * 
 * @link http://www.google.com/cse/docs/resultsxml.html
 */
class BodyLine extends AbstractData
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @var string
     */
    protected $link;
    
    /**
     * Gets the text.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Gets the URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Gets the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}