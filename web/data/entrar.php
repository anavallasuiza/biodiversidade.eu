<?php
defined('ANS') or die();

if ($user) {
    redirect(path('perfil'));
}

$usuarios = $Db->select(array(
    'table' => 'usuarios',
    'fields' => array('nome', 'apelido1', 'apelido2', 'especialidade'),
    'group' => 'usuarios.id',
    'conditions' => array(
        'roles.code' => array('nivel-3', 'editor'),
        'activo' => 1
    )
));

array_walk($usuarios, function (&$value) {
    $value['nome_completo'] = trim($value['nome']['title'].' '.$value['apelido1'].' '.$value['apelido2']);
});

$Html->meta('title', __('Acceso de usuario'));
