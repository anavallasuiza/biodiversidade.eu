<?php
defined('ANS') or die();

if (empty($log)) {
    return '';
}

$autor = $Html->a($log['usuarios_autor']['nome']['title'].' '.$log['usuarios_autor']['apelido1'], path('perfil', $log['usuarios_autor']['nome']['url']));

switch ($log['action']) {
    case 'comentar':
        __e('% fixo un comentario en %s', $autor);
}
