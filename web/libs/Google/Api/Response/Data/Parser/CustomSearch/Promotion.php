<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Parser\CustomSearch;

use Google\Api\Response\Data\CustomSearch\Promotion as PromotionData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\CustomSearch\Promotion\BodyLine as BodyLineParser;
use Google\Api\Response\Data\Parser\CustomSearch\Promotion\Image as ImageParser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Promotion parses raw data into a formatted Promotion Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Promotion implements Parser
{
    /**
     * Parses raw data into a formatted Promotion Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return PromotionData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->title) && is_string($data->title) && strlen($data->title) > 0)) {
            throw new Exception('Missing/invalid promotion title.');
        }
        
        $formattedData['title'] = $data->title;
        
        if(!(isset($data->link) && is_string($data->link) && strlen($data->link) > 0)) {
            throw new Exception('Missing/invalid promotion link.');
        }
        
        $formattedData['link'] = $data->link;
        
        if(!(isset($data->displayLink) && is_string($data->displayLink) && strlen($data->displayLink) > 0)) {
            throw new Exception('Missing/invalid promotion display link.');
        }
        
        $formattedData['displayLink'] = $data->displayLink;
        
        if(isset($data->bodyLines)) {

            if(!(is_array($data->bodyLines) && count($data->bodyLines) > 0)) {
                throw new Exception('Invalid promotion block objects (body lines).');
            }
            
            $formattedData['bodyLines'] = $this->parseBodyLines();
        }
        
        if(isset($data->image)) {
            
            if(!($data->image instanceof \stdClass)) {
                throw new Exception('Invalid promotion image.');
            }
            
            $formattedData['image'] = $this->parseImage();
        }

        return new PromotionData($formattedData);
    }

    /**
     * Gets the BodyLine parser used by this parser.
     *
     * @return BodyLineParser
     */
    protected function getBodyLineParser()
    {
        return new BodyLineParser();
    }
    
    /**
     * Parses the "bodyLines" part of the data.
     *
     * @param \stdClass $bodyLines
     *
     * @return array
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parseBodyLines(array $bodyLines)
    {
        $bodyLineObjects = array();
        $bodyLineParser = $this->getBodyLineParser();

        foreach($bodyLines as $type => $bodyLine) {
            
            if(!($bodyLine instanceof \stdClass)) {
                throw new Exception('Invalid promotion block object (body line).');
            }
            
            array_push($bodyLineObjects, $bodyLineParser->parse($bodyLine));
        }
        
        return $bodyLineObjects;
    }

    /**
     * Gets the Image parser used by this parser.
     *
     * @return ImageParser
     */
    protected function getImageParser()
    {
        return new ImageParser();
    }
    
    /**
     * Parses the "image" part of the data.
     *
     * @param \stdClass $image
     *
     * @return \Google\Api\Response\Data\CustomSearch\Promotion\Image
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parseImage(\stdClass $image)
    {
        return $this->getImageParser()->parse($image);
    }
}