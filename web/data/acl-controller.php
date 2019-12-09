<?php
defined('ANS') or die();

$controller = is_array($controller) ? implode('/', $controller) : $controller;

if (empty($controller) || $Acl->check('controller', $controller) !== true) {
    if (strstr(SERVER_NAME, 'localhost')) {
        die(__('Sorry but you haven\'t permissions to view this section (%s) controlled by %s.', getenv('REQUEST_URI'), $controller));
    }

    $Vars->message(__('Sorry but you haven\'t permissions to view this section (%s).', getenv('REQUEST_URI')), 'ko');

    if (ROUTE === 'index') {
        redirect(path('acceso'));
    } else {
        referer(path(''), true, path('acceso'));
    }
}
