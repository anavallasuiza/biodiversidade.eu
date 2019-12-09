<?php
defined('ANS') or die();

if (empty($action) || $Acl->check('action', $action) !== true) {
    if (strstr(SERVER_NAME, 'localhost')) {
        die(__('Sorry but you haven\'t permissions to execute action %s.', $action));
    }

    $Vars->message(__('Sorry but you haven\'t permissions to execute this action.'), 'ko');

    referer(path(''), true, path('users', 'login'));
}
