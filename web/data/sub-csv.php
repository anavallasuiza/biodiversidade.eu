<?php
defined('ANS') or die();

if (empty($fields) || empty($name) || empty($data) || !is_array($data[0])) {
    die('');
}

define('SEPARADOR', ',');

$exclude = is_array($exclude) ? $exclude : ($exclude ? array($exclude) : array());
$header = array_keys($data[0]);

if (is_string($fields)) {
    if ($fields === '*') {
        $fields = $header;
    } else {
        $fields = array($fields);
    }
} else if ($fields[0] === '*') {
    unset($fields[0]);

    array_unshift($fields, $header);
}

$csv = '';

foreach ($fields as $fields_key => &$fields_value) {
    if (($fields_value === 'id') || ($exclude && in_array($fields_value, $exclude))) {
        unset($fields[$fields_key]);
        continue;
    }

    if (strstr($fields_value, ':')) {
        $fields_value = explode(':', $fields_value);
        $table = str_replace('_autor', '', $fields_value[0]);
        $csv .= '"'.str_replace('"', '""', __($fields_value[1]).' ('.__($table).')').'"' . SEPARADOR;
    } else if (is_string($data[0][$fields_value])) {
        $csv .= '"'.str_replace('"', '""', __($fields_value)).'"' . SEPARADOR;
    } else {
        unset($fields[$fields_key]);
    }
}

unset($fields_value);

foreach ($data as $data_value) {
    $csv .= "\n";

    foreach ($fields as $fields_value) {
        if (is_array($fields_value)) {
            if (isset($data_value[$fields_value[0]][$fields_value[1]])) {
                $csv .= '"'.str_replace('"', '""', $data_value[$fields_value[0]][$fields_value[1]]).'"' . SEPARADOR;
            } else {
                $csv .= '""' . SEPARADOR;
            }
        } else if (array_key_exists($fields_value, $data_value)) {
            $csv .= '"'.str_replace('"', '""', $data_value[$fields_value]).'"' . SEPARADOR;
        }
    }
}

$csv = trim(str_replace(SEPARADOR . "\n", "\n", $csv));

header('Pragma: private');
header('Expires: 0');
header('Cache-control: private, must-revalidate');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Type: application/force-download');
header('Content-Transfer-Encoding: binary');
header('Content-Disposition: attachment; filename="'.$name.'.csv"');

header('Content-Length: '.strlen($csv));

die($csv);
