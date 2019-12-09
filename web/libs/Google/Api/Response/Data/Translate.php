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
 * Translate contains the data from a Google Translate API response.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Translate extends AbstractData
{
    /**
     * @var array
     */
    protected $translations = array();
    
    /**
     * Determines if there are translations.
     *
     * @return array
     */
    public function hasTranslations()
    {
        return count($this->getTranslations()) > 0;
    }

    /**
     * Gets the translations.
     *
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}