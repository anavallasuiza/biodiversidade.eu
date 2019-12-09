<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\CustomSearch\Item;

use Google\Api\Response\Data\AbstractData;

/**
 * PageMap is a single item PageMap from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class PageMap extends AbstractData
{
    /**
     * @var array
     */
    protected $data;
    
    /**
     * Sets the passed data on the object.
     *
     * @param array $data
     */
    protected function setData(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * Gets all the data.
     * 
     * @return array 
     */
    public function getAll()
    {
        return $this->data;
    }
    
    /**
     * Gets a specific property.
     * 
     * @param string $property
     *
     * @return string
     */
    public function getProperty($property)
    {
        if(isset($this->data[$property])) {
            return $this->data[$property];
        }

        return null;
    }
}