<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Parser\CustomSearch\Promotion;

use Google\Api\Response\Data\CustomSearch\Promotion\Image as ImageData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Image parses raw data into a formatted Image Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Image implements Parser
{
    /**
     * Parses raw data into a formatted Image Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return ImageData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->source) && is_string($data->source) && strlen($data->source) > 0)) {
            throw new Exception('Missing/invalid promotion image source.');
        }
        
        $formattedData['label'] = $data->label;
        
        if(!(isset($data->width) && is_int($data->width) && $data->width > 0)) {
            throw new Exception('Missing/invalid promotion image width.');
        }
        
        $formattedData['width'] = $data->width;
        
        if(!(isset($data->height) && is_int($data->height) && $data->height > 0)) {
            throw new Exception('Missing/invalid promotion image width.');
        }
        
        $formattedData['height'] = $data->height;

        return new ImageData($formattedData);
    }
}