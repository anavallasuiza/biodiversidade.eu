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

use Google\Api\Response\Data\CustomSearch\Query as QueryData;
use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Query parses raw data into a formatted Query Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Query implements Parser
{
    /**
     * Parses raw data into a formatted Query Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return QueryData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();
        
        if(!(isset($data->totalResults) && is_numeric($data->totalResults))) {
            throw new Exception('Missing/invalid query total results.');
        }
        
        foreach($data as $key => $value) {
            $formattedData[$key] = $value;
        }

        return new QueryData($formattedData);
    }
}