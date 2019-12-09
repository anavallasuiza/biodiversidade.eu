<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

include (__DIR__.'/libs/ANS/Cache/Cache.php');
include (__DIR__.'/settings-example.php');

$Cache = new \ANS\Cache\Cache($settings['js']);

if ($Cache->exists('js-files')) {
    $content = $Cache->get('js-files');
} else {
    $content =  file_get_contents('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    $content .= file_get_contents('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js');
    $content .= file_get_contents('http://modernizr.com/i/js/modernizr.com-custom-1.6.js');

    // Cache expired time is loaded from settings
    // but you can set your own time in seconds from now
    // Third parameter is optional
    $Cache->set('js-files', $content, $custom_time);
}

echo $content;
