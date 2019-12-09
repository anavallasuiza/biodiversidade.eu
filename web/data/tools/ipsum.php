<?php
defined('ANS') or die();

echo '<pre>';
echo '<h1>Started at '.date('Y-m-d H:i:s').'</h1>';

flush(); ob_flush();

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$Ipsum = new \ANS\PHPCan\Utils\Ipsum;

$Ipsum->setSettings(array(
    'truncate' => true,
    'tables' => $Config->tables[getDatabaseConnection()],
    'relations' => $Config->relations[getDatabaseConnection()]
));

$Ipsum->fill(array(
    array('comunidade', 30),
));

$Ipsum->relate(array(
    array('comunidade', 'usuarios', 'autor')
));

die('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
