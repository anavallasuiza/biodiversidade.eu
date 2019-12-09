<?php
defined('ANS') or die();

if (empty($user) && !in_array('editor', arrayKeyValues($user['roles'], 'code'))) {
    $Vars->message(__('Sentímolo, pero non tes permisos para editar este contido.'), 'ko');
    referer(path(''));
}

$data = $Vars->var;

if (empty($data['grupos']) || empty($data['clases']) || empty($data['ordes']) || empty($data['familias']) || empty($data['nome'])) {
    $Vars->message(__('Os campos de grupo, clase, orde, familia e nome do xénero son obrigatorios'), 'ko');
    return false;   
}

$idXenero = $Db->insert(array(
    'table' => 'xeneros',
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
        ),
        array(
            'table' => 'familias',
            'limit' => 1,
            'conditions' => array(
                'url' => $data['familias']
            )
        )
    )
));

if (empty($idXenero)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

$Vars->message(__('O novo xénero foi creado correctamente'), 'ok');
return true;