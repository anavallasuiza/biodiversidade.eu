<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\CustomSearch\Context;

use Google\Api\Response\Data\AbstractData;

/**
 * Facet is a single context facet from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 * 
 * @link https://code.google.com/apis/customsearch/docs/refinements.html#create
 */
class Facet extends AbstractData
{
    /**
     * @var string
     */
    protected $label;
    
    /**
     * @var string
     */
    protected $anchor;
    
    /**
     * Gets the label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->title;
    }
    
    /**
     * Gets the displayable name.
     *
     * @return string
     */
    public function getAnchor()
    {
        return $this->anchor;
    }
}