<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Parser\CustomSearch\Item;

use Google\Api\Response\Data\CustomSearch\Item\PageMap as PageMapData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * PageMap parses raw data into a formatted PageMap Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class PageMap implements Parser
{
    /**
     * Parses raw data into a formatted PageMap Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return PageMapData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        foreach($data as $key => $value) {
            $formattedData[$key] = $value;
        }

        return new PageMapData($formattedData);
    }
}