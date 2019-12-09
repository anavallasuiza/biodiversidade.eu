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

/**
 * AbstractData contains common functionality for all API data objects.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
abstract class AbstractData
{
    /**
     * Constructs a new Data object.
     *
     * @param array $data The data to store in the Data object.
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }

    /**
     * Sets the passed data on the object.
     *
     * @param array $data
     */
    protected function setData(array $data)
    {
        foreach($data as $property => $value) {
            
            if(!property_exists($this, $property)) {
                throw new \UnexpectedValueException(sprintf('Property "%s" does not exist on data object.', $property));
            }

            $this->{$property} = $value;
        }
    }
}