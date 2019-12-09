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
 * Image is a single promotion image from the Google Custom Search API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Image extends AbstractData
{
    /**
     * @var string
     */
    protected $source;
    
    /**
     * @var integer
     */
    protected $width;
    
    /**
     * @var integer
     */
    protected $height;
    
    /**
     * Gets the URL of the image.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Gets the width.
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * Gets the height.
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }
}