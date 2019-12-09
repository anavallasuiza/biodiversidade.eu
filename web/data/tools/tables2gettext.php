<?php
defined('ANS') or die();

$file = filePath('languages|dynamic-strings.php');

if (!is_writable($file)) {
    die($file.' is not writable');
}

$array = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$text = implode('', $array);

$tables = $Config->tables[getDatabaseConnection()];

foreach ($tables as $tables_value) {
    foreach ($tables_value as $fields_value) {
        if (is_string($fields_value) || empty($fields_value['values'])) {
            continue;
        }

        foreach ($fields_value['values'] as $values_value) {
            if (!strstr($text, "('".$values_value."')")) {
                $array[] = "__('".$values_value."');";
            }
        }
    }
}

file_put_contents($file, implode("\n", array_unique($array))."\n");

die();
