<?php
defined('ANS') or die();

if (!$categoria) {
    return '';
}

echo '<label>'.$categoria['nome'].'</label>';

$type = $categoria['multiple'] ? 'checkbox' : 'radio';

foreach ($categoria['opcions'] as $opcion) {
    echo $Form->$type(array(
        'name' => 'opcions[]',
        'id' => 'opcions['.$opcion['id'].']',
        'value' => $opcion['id'],
        'selected' => (in_array($opcion['id'], $Vars->var['opcions']) ? 'selected' : ''),
        'label_text' => $opcion['nome'],
        'force' => false
    ));
}
