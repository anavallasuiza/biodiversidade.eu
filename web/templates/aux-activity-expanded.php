<?php
defined('ANS') or die();

if (empty($log)) {
    return '';
}

if (empty($Config->routes2tables)) {
	$Config->load('routes2tables.php');
}

$autor = $Html->a($log['usuarios_autor']['nome']['title'].' '.$log['usuarios_autor']['apelido1'], path('perfil', $log['usuarios_autor']['nome']['url']));

$table1 = $log[$log['related_table']];
$table2 = $log[$log['related_table2']];

$relation1 = $Config->tables2routes[$log['related_table']];
$relation2 = $Config->tables2routes[$log['related_table2']];

$template = 'aux-act-'.$log['action'].'.php';

if ($Templates->exists($template)) {
    include ($Templates->file($template));
}
