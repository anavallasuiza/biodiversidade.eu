<?php

/*
 * This file is part of the Google APIs PHP Clients package.
 *
 * (c) Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Google\Api;

use Google\Api\Response\Data\Parser\Translate as DataParser;

/**
 * Translate is the main client class for the Google Translate API.
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 *
 * @link http://code.google.com/apis/language/translate/v2/using_rest.html
 */
class Translate extends AbstractApi
{
    const API_URL = 'https://www.googleapis.com/language/translate/v2';
    
    const PARAMETER_API_KEY = 'key';
    const PARAMETER_FORMAT = 'format';
    const PARAMETER_PRETTYPRINT = 'prettyprint';
    const PARAMETER_SOURCE_TEXT = 'q';
    const PARAMETER_SOURCE_LANGUAGE = 'source';
    const PARAMETER_TARGET_LANGUAGE = 'target';

    const FORMAT_HTML = 'html';
    const FORMAT_TEXT = 'text';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var array
     */
    protected $sourceText = array();

    /**
     * @var string
     */
    protected $sourceLanguage;

    /**
     * @var string
     */
    protected $targetLanguage;

    /**
     * Constructs a new Google Translate API client.
     * 
     * @param array|string $sourceText A single string or an array of strings to translate.
     * @param Adapter $adapter The adapter used to make the API request.
     */
    public function __construct($sourceText = null, Adapter $adapter = null)
    {
        if($sourceText !== null) {
            $this->setSourceText($sourceText);
        }

        parent::__construct($adapter);
    }

    /**
     * Gets the API key to used for the request.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the API key to used for the request.
     *
     * @param string $apiKey
     *
     * @return Translate
     *
     * @throws \InvalidArgumentException
     *
     * @link https://code.google.com/apis/console
     */
    public function setApiKey($apiKey)
    {
        if(!(is_string($apiKey) && strlen(trim($apiKey)) > 0)) {
            throw new \InvalidArgumentException('Invalid API key. Please provide a non-empty string.');
        }

        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Gets the format of the string(s) to be translated.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets the format of the string(s) to be translated.
     * 
     * @param string $format
     * 
     * @return Translate
     *
     * @throws \InvalidArgumentException
     *
     * @see FORMAT_HTML, FORMAT_TEXT
     */
    public function setFormat($format = null)
    {
        if($format !== null) {

            if(is_bool($format)) {
                throw new \InvalidArgumentException('Invalid format. Please provide either "text" or "html".');
            }

            switch($format) {
                case self::FORMAT_HTML:
                case self::FORMAT_TEXT:
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid format. Please provide either "text" or "html".');#
                    break;
            }
        }

        $this->format = $format;
        return $this;
    }
    
    /**
     * Gets the strings to be translated.
     *
     * @return array
     */
    public function getSourceText()
    {
        return $this->sourceText;
    }

    /**
     * Sets the string(s) to be translated.
     *
     * @param string|array $sourceText A single string or an array of strings to translate.
     *
     * @return Translate
     *
     * @throws \InvalidArgumentException
     */
    public function setSourceText($sourceText)
    {
        if(is_string($sourceText)) {
            $sourceText = array($sourceText);
        }

        if(!is_array($sourceText) || count($sourceText) == 0) {
            throw new \InvalidArgumentException('Invalid source text. Please provide either an array of or a single non-empty string.');
        }

        foreach($sourceText as $key => $singleSourceText) {
            if(!$this->validateSourceText($singleSourceText)) {
                throw new \InvalidArgumentException(sprintf('Invalid source text'.(count($sourceText) != 1 ? ' at index "%s"' : null).'. Please provide a non-empty string.', $key));
            }
        }

        $this->sourceText = $sourceText;
        return $this;
    }

    /**
     * Adds a string to be translated.
     *
     * @param string $sourceText
     *
     * @return Translate
     *
     * @throws \InvalidArgumentException
     */
    public function addSourceText($sourceText)
    {
        if(!$this->validateSourceText($sourceText)) {
            throw new \InvalidArgumentException('Invalid source text. Please provide a non-empty string.');
        }

        array_push($this->sourceText, $sourceText);
        return $this;
    }

    /**
     * Validates a string to be translated.
     *
     * @param string $sourceText
     *
     * @return boolean
     */
    protected function validateSourceText($sourceText)
    {
        return is_string($sourceText) && strlen(trim($sourceText)) > 0;
    }

    /**
     * Gets the source language code of the string(s) to translate.
     *
     * @return string
     */
    public function getSourceLanguage()
    {
        return $this->sourceLanguage;
    }

    /**
     * Sets the source language code of the string(s) to translate.
     *
     * Note: If not specified, the system will attempt to identify the source language code automatically.
     *
     * @param string $sourceLanguage
     *
     * @return Translate
     *
     * @throws \InvalidArgumentException
     *
     * @see static::getAvailableLanguages()
     */
    public function setSourceLanguage($sourceLanguage = null)
    {
        if($sourceLanguage !== null && !(is_string($sourceLanguage) && array_key_exists($sourceLanguage, static::getAvailableLanguages()))) {
            throw new \InvalidArgumentException('Invalid source language. Please provide a valid language code.');
        }

        $this->sourceLanguage = $sourceLanguage;
        return $this;
    }

    /**
     * Gets the target language code the string(s) will be translated into.
     *
     * @return string
     */
    public function getTargetLanguage()
    {
        return $this->targetLanguage;
    }

    /**
     * Sets the target language code the string(s) will be translated into.
     *
     * @param string $targetLanguage
     *
     * @return Translate
     *
     * @throws \InvalidArgumentException
     *
     * @see static::getAvailableLanguages()
     */
    public function setTargetLanguage($targetLanguage)
    {
        if(!(is_string($targetLanguage) && array_key_exists($targetLanguage, static::getAvailableLanguages()))) {
            throw new \InvalidArgumentException('Invalid source language. Please provide a valid language code.');
        }

        $this->targetLanguage = $targetLanguage;
        return $this;
    }

    /**
     * Gets a list of languages the Google Translate API supports.
     *
     * @return array
     *
     * @link http://code.google.com/apis/language/translate/v2/using_rest.html#language-params
     */
    public static function getAvailableLanguages()
    {
        return array(
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'ar' => 'Arabic',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'zh-CN' => 'Chinese Simplified',
            'zh-TW' => 'Chinese Traditional',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'et' => 'Estonian',
            'tl' => 'Filipino',
            'fi' => 'Finnish',
            'fr' => 'French',
            'gl' => 'Galician',
            'de' => 'German',
            'el' => 'Greek',
            'ht' => 'Haitian Creole',
            'iw' => 'Hebrew',
            'hi' => 'Hindi',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'id' => 'Indonesian',
            'ga' => 'Irish',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'mk' => 'Macedonian',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'no' => 'Norwegian',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'sr' => 'Serbian',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'es' => 'Spanish',
            'sw' => 'Swahili',
            'sv' => 'Swedish',
            'th' => 'Thai',
            'tr' => 'Turkish',
            'uk' => 'Ukrainian',
            'vi' => 'Vietnamese',
            'cy' => 'Welsh',
            'yi' => 'Yiddish'
        );
    }

    /**
     * Validates that all parameters are valid for for the API request.
     * 
     * @return boolean
     *
     * @throws \LogicExcetion
     */
    protected function validateParameters()
    {
        if($this->getApiKey() === null) {
            throw new \RuntimeException('Missing required parameter "API key".');
        }

        if(count($this->getSourceText()) == 0) {
            throw new \RuntimeException('Missing required parameter "source text".');
        }

        if($this->getTargetLanguage() === null) {
            throw new \RuntimeException('Missing required parameter "target language".');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDataParser()
    {
        return new DataParser();
    }

    /**
     * Gets the API request data.
     * 
     * @return array
     */
    protected function getApiRequestData()
    {
        return array(
            self::PARAMETER_PRETTYPRINT     => false,
            self::PARAMETER_FORMAT          => $this->getFormat(),
            self::PARAMETER_API_KEY         => $this->getApiKey(),
            self::PARAMETER_SOURCE_TEXT     => $this->getSourceText(),
            self::PARAMETER_SOURCE_LANGUAGE => $this->getSourceLanguage(),
            self::PARAMETER_TARGET_LANGUAGE => $this->getTargetLanguage()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiUrl()
    {
        return self::API_URL;
    }
}