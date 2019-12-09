<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data;

use Google\Api\Response\Data\AbstractData;
use Google\Api\Response\Data\Parser\Exception;

/**
 * Parser parses some raw data into a formatted Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
interface Parser
{
    /**
     * Parses some raw data into a formatted Data object.
     *
     * @param \stdClass $data The data to parse.
     * 
     * @return AbstractData A formatted object.
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data);
}