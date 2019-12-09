<?php
defined('ANS') or die();

if (empty($user) && !in_array('editor', arrayKeyValues($user['roles'], 'code'))) {
    $Vars->message(__('Sentímolo, pero non tes permisos para editar este contido.'), 'ko');
    referer(path(''));
}

$data = $Vars->var;

if (empty($data['grupos']) || empty($data['clases']) || empty($data['ordes']) || empty($data['nome'])) {
    $Vars->message(__('Os campos de grupo, clase, orde e nome da familia son obrigatorios'), 'ko');
    return false;   
}

$idFamilia = $Db->insert(array(
    'table' => 'familias',
    'data' => array(
        'url' => $data['nome'],
        'nome' => $data['nome']
    ),
    'relate' => array(
        array(
            'table' => 'grupos',
            'limit' => 1,
            'conditions' => array(
                'url' => $data['grupos']
            )
        ),
        array(
            'table' => 'clases',
            'limit' => 1,
            'conditions' => array(
                'url' => $data['clases']
            )
        ),
        array(
            'table' => 'ordes',
            'limit' => 1,
            'conditions' => array(
                'url' => $data['ordes']
            )
        )
    )
));

if (empty($idFamilia)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

$Vars->message(__('A nova familia foi creada correctamente'), 'ok');
return true;