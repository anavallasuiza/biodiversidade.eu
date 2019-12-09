<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-proxecto-editar.php'));

$Data->execute('acl-action.php', array('action' => 'proxecto-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'proxectos',
    'id' => $proxecto['id'],
    'action' => 'eliminar',
    'content' => $proxecto
));

$Db->delete(array(
    'table' => 'cadernos',
    'conditions' => array(
        'proxectos.id' => $proxecto['id']
    )
));

$Db->delete(array(
    'table' => 'comentarios',
    'conditions' => array(
        'proxectos.id' => $proxecto['id']
    )
));

$Db->delete(array(
    'table' => 'imaxes',
    'conditions' => array(
        'proxectos.id' => $proxecto['id']
    )
));

$Db->delete(array(
    'table' => 'adxuntos',
    'conditions' => array(
        'proxectos.id' => $proxecto['id']
    )
));

$Db->delete(array(
    'table' => 'proxectos',
    'limit' => 1,
    'conditions' => array(
        'id' => $proxecto['id']
    )
));


$Vars->message(__('O proxecto e todos os seus cadernos foron eliminados correctamente.'), 'ok');

redirect(path('blogs'));