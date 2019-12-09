<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Parser\CustomSearch\Context;

use Google\Api\Response\Data\CustomSearch\Context\Facet as FacetData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Facet parses raw data into a formatted Facet Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Facet implements Parser
{
    /**
     * Parses raw data into a formatted Facet Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return FacetData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->label) && is_string($data->label && strlen($data->label) > 0))) {
            throw new Exception('Missing/invalid context facet label.');
        }
        
        $formattedData['label'] = $data->label;
        
        if(!(isset($data->anchor))) {
            throw new Exception('Missing/invalid context facet anchor.');
        }
        
        $formattedData['anchor'] = $data->anchor;

        return new FacetData($formattedData);
    }
}