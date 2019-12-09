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

use Google\Api\Response\Data\CustomSearch\Item as ItemData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\CustomSearch\Item\PageMap as PageMapParser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Item parses raw data into a formatted Item Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Item implements Parser
{
    const KIND = 'customsearch#result';
    
    /**
     * Parses raw data into a formatted Item Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return ItemData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->kind) && $data->kind === self::KIND)) {
            throw new Exception(sprintf('Missing/invalid item kind. Expected "%s".', self::KIND));
        }
        
        if(!(isset($data->title) && is_string($data->title) && strlen($data->title) > 0)) {
            throw new Exception('Missing/invalid item title.');
        }
        
        $formattedData['title'] = $data->title;
        
        if(!(isset($data->htmlTitle) && is_string($data->htmlTitle) && strlen($data->htmlTitle) > 0)) {
            throw new Exception('Missing/invalid item HTML title.');
        }
        
        $formattedData['htmlTitle'] = $data->htmlTitle;
        
        if(!(isset($data->link) && is_string($data->link) && strlen($data->link) > 0)) {
            throw new Exception('Missing/invalid item link.');
        }
        
        $formattedData['link'] = $data->link;
        
        if(!(isset($data->displayLink) && is_string($data->displayLink) && strlen($data->displayLink) > 0)) {
            throw new Exception('Missing/invalid item display link.');
        }
        
        $formattedData['displayLink'] = $data->displayLink;
        
        if(isset($data->snippet)) {

            if(!(is_string($data->snippet) && strlen($data->snippet) > 0)) {
                throw new Exception('Invalid item snippet.');
            }
            
            $formattedData['snippet'] = $data->snippet;
        }
        
        if(isset($data->htmlSnippet)) {

            if(!(is_string($data->htmlSnippet) && strlen($data->htmlSnippet) > 0)) {
                throw new Exception('Invalid item HTML snippet.');
            }
            
            $formattedData['htmlSnippet'] = $data->htmlSnippet;
        }
        
        if(isset($data->cacheId)) {

            if(!(is_string($data->cacheId) && strlen($data->cacheId) > 0)) {
                throw new Exception('Invalid item cache ID.');
            }
            
            $formattedData['cacheId'] = $data->cacheId;
        }
        
        if(isset($data->pagemap)) {

            if(!($data->pagemap instanceof \stdClass)) {
                throw new Exception('Invalid item PageMap.');
            }
            
            $formattedData['pagemap'] = $this->parsePageMap($data->pagemap);
        }

        return new ItemData($formattedData);
    }

    /**
     * Gets the PageMap parser used by this parser.
     *
     * @return PageMapParser
     */
    protected function getPageMapParser()
    {
        return new PageMapParser();
    }
    
    /**
     * Parses the "pagemap" part of the data.
     *
     * @param \stdClass $pagemap
     *
     * @return \Google\Api\Response\Data\CustomSearch\Item\PageMap
     *
     * @throws Exception When a parse error occurs.
     */
    protected function parsePageMap(\stdClass $pagemap)
    {
        $pageMapObjects = array();
        $pageMapParser = $this->getPageMapParser();

        foreach($pagemap as $name => $pagemapData) {
            
            if(!(is_array($pagemapData) && count($pagemapData) === 1 && array_key_exists(0, $pagemapData) && $pagemapData[0] instanceof \stdClass)) {
                throw new Exception('Invalid item PageMap data.');
            }
            
            $pageMapObjects[$name] = $pageMapParser->parse($pagemapData[0]);
        }
        
        return $pageMapObjects;
    }
}