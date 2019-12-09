<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Translate;

use Google\Api\Response\Data\AbstractData;

/**
 * Translation is a single translation from the Google Translate API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Translation extends AbstractData
{
    /**
     * @var string
     */
    protected $translatedText;

    /**
     * @var string
     */
    protected $detectedSourceLanguage;

    /**
     * Gets the actual translated text.
     *
     * @return string
     */
    public function getTranslatedText()
    {
        return $this->translatedText;
    }

    /**
     * Gets the detected language of the source text.
     * Note: This is only set if the source language was not specified in the request.
     *
     * @return string
     */
    public function getDetectedSourceLanguage()
    {
        return $this->detectedSourceLanguage;
    }
}