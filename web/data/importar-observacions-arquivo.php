<?php 
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

$Data->execute('acl-action.php', array('action' => 'importar-observacions'));


$Html->meta('title', __('Importar observacións'));