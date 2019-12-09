<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-didactica.php'));
    $Data->execute('acl-action.php', array('action' => 'didactica-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'didactica-crear'));
    $didactica = array();
}
