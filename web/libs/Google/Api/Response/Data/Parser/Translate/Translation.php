<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api\Response\Data\Parser\Translate;

use Google\Api\Response\Data\Parser;
use Google\Api\Response\Data\Parser\Exception;
use Google\Api\Response\Data\Translate\Translation as TranslationData;

/**
 * Translation parses raw data into a formatted Translation Data object.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
class Translation implements Parser
{
    /**
     * Parses raw data into a formatted Translation Data object.
     *
     * @param \stdClass $data The data to parse.
     *
     * @return TranslationData
     *
     * @throws Exception When a parse error occurs.
     */
    public function parse(\stdClass $data)
    {
        $formattedData = array();

        if(!(isset($data->translatedText) && is_string($data->translatedText))) {
            throw new Exception('Missing/invalid translation translated text.');
        }

        $formattedData['translatedText'] = $data->translatedText;

        if(isset($data->detectedSourceLanguage)) {
            
            if(!(is_string($data->detectedSourceLanguage) && strlen($data->detectedSourceLanguage) > 0)) {
                throw new Exception('Invalid translation detected source language.');
            }

            $formattedData['detectedSourceLanguage'] = $data->detectedSourceLanguage;
        }

        return new TranslationData($formattedData);
    }
}