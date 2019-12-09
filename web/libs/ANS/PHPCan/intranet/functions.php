<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

function encode2utf ($string) {
    if ((mb_detect_encoding($string) == 'UTF-8') && mb_check_encoding($string, 'UTF-8')) {
        return $string;
    } else {
        return utf8_encode($string);
    }
}

function encode2iso ($string) {
    if ((mb_detect_encoding($string) == 'ISO-8859-1') && mb_check_encoding($string, 'ISO-8859-1')) {
        return $string;
    } else {
        return @mb_convert_encoding($string, 'ISO-8859-1', 'auto');
    }
}

function getCSVline ($line) {
    $line = trim($line);

    if (!$line) {
        return array();
    }

    $return = array();

    foreach (explode(';', encode2utf($line)) as $column) {
        $return[] = trim(preg_replace('/\s+/', ' ', $column));
    }

    return $return;
}