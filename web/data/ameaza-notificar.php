<?php
defined('ANS') or die();

include ($Data->file('acl-ameaza.php'));

if ($ameaza['notificada']) {
    $Vars->message(__('Esta ameaza xa foi notificada'), 'ko');
    referer(path('ameazas'));
}

$paises = $Db->select(array(
    'table' => 'paises',
    'fields' => '*'
));

$territorios = $Db->select(array(
    'table' => 'territorios',
    'fields' => '*',
    'conditions' => array(
        'activo' => 1
    )
));
