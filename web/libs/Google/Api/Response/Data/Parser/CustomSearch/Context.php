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

use Google\Api\Response\Data\CustomSearch\Context as ContextData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\CustomSearch\Context\Facet as FacetParser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Context parses raw data into a formatted Context Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Context implements Parser
{
    /**
     * Parses raw data into a formatted Context Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return ContextData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->title) && is_string($data->title) && strlen($data->title) > 0)) {
            throw new Exception('Missing/invalid context title.');
        }
        
        $formattedData['title'] = $data->title;
        
        if(isset($data->facets) && is_array($data->facets) && count($data->facets) > 0) {
            $formattedData['facets'] = $this->parseFacets($data->facets);
        }

        return new ContextData($formattedData);
    }

    /**
     * Gets the Facet parser used by this parser.
     *
     * @return FacetParser
     */
    protected function getFacetParser()
    {
        return new FacetParser();
    }
    
    /**
     * Parses the "facet" part of the context data.
     *
     * @param array $facets
     *
     * @return array
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parseFacets(array $facets)
    {
        $facetObjects = array();
        $facetParser = $this->getFacetParser();

        foreach($facets as $facet) {
            
            if(!(is_array($facet) && count($facet) === 1 && array_key_exists(0, $facet) && $facet[0] instanceof \stdClass)) {
                throw new Exception('Invalid facet format.');
            }

            array_push($facetObjects, $facetParser->parse($facet[0]));
        }
        
        return $facetObjects;
    }
}