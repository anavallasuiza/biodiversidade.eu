<?php
defined('ANS') or die();

if (isset($Vars->var['arquivo']['tmp_name'])) {
    include ($Data->file('acl-arquivo-editar.php'));
} else {
    $Data->execute('acl-action.php', array('action' => 'arquivo-crear'));
    $resultado = array();
}

$Html->meta('title', __('Subida de arquivo'));
