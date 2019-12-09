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

use Google\Api\Response\Data\CustomSearch\Promotion\BodyLine as BodyLineData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * BodyLine parses raw data into a formatted BodyLine Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class BodyLine implements Parser
{
    /**
     * Parses raw data into a formatted BodyLine Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return BodyLineData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(isset($data->title)) {

            if(!(is_string($data->title) && strlen($data->title) > 0)) {
                throw new Exception('Invalid promotion block object title.');
            }
            
            $formattedData['title'] = $data->title;
        }
        
        if(isset($data->url)) {

            if(!(is_string($data->url) && strlen($data->url) > 0)) {
                throw new Exception('Invalid promotion block object URL.');
            }
            
            $formattedData['url'] = $data->url;
        }
        
        if(isset($data->link)) {
            
            if(!(is_string($data->link) && strlen($data->link) > 0)) {
                throw new Exception('Invalid promotion block object link.');
            }
            
            $formattedData['link'] = $data->link;
        }

        return new BodyLineData($formattedData);
    }
}