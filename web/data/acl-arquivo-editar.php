<?php
defined('ANS') or die();

$resultado = array();
$arquivo = $Vars->var['arquivo'];

if ($arquivo['tmp_name'] && is_file($arquivo['tmp_name'])) {
    if (strtolower(end(explode('.', $arquivo['name']))) !== 'csv') {
        $Vars->message(__('El fichero que has enviado no parece ser fichero CSV válido, revisa que la extensión del mismo sea "csv".'), 'ko');
        return false;
    }

    $finfo = finfo_open(FILEINFO_MIME);

    if (substr(finfo_file($finfo, $arquivo['tmp_name']), 0, 4) !== 'text') {
        $Vars->message(__('El fichero que has enviado no parece ser fichero CSV válido.'), 'ko');
        return false;
    }

    $arquivo = file($arquivo['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $header = array_shift($arquivo);
} else {
    $arquivo = array();
}

$Geocoder = new \Geocoder\Geocoder();
$Geocoder->registerProviders(array(
    new \Geocoder\Provider\OpenStreetMapsProvider(
        new \Geocoder\HttpAdapter\CurlHttpAdapter()
    )
));

/*
Array
(
    [0] => ESPECIES
    [1] => Localidade
    [2] => UTM MGRS
    [3] => FUSO
    [4] => UTM_X
    [5] => UTM_Y
    [6] => Lat
    [7] => Long
    [8] => Datum
    [9] => Tipo de Dado
    [10] => Arquivo
    [11] => Tipo de referencia
    [12] => Referencia bibliográfica
    [13] => Fornecedor de Datos
)
*/

$listado = $especie = array();

foreach ($arquivo as $i => $fila) {
    $csv = trimArray(str_getcsv($fila, ';', '"'));

    if (empty($csv[0])) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');

    $code = str_replace('_', '', $csv[0]);
    $code = alphaNumeric($code);

    if (empty($listado[$code])) {
        $listado[$code] = array(
            'i' => $i,
            'nome' => $csv[0],
            'especie' => array(),
            'fichas' => array()
        );
    }

    $md5 = $csv;

    unset($md5[2], $md5[3], $md5[4], $md5[5], $md5[6], $md5[7], $md5[8]);

    $md5 = md5(serialize($md5));

    if (empty($listado[$code]['fichas'][$md5])) {
        $listado[$code]['fichas'][$md5] = array(
            'localidade' => $csv[1],
            'tipo' => $csv[9],
            'arquivo' => $csv[10],
            'tipo_referencia' => $csv[11],
            'referencia' => $csv[12],
            'fornecedor' => $csv[13],
            'puntos' => array(array(
                'mgrs' => $csv[2],
                'fuso' => $csv[3],
                'utm_x' => $csv[4],
                'utm_y' => $csv[5],
                'lat' => $csv[6],
                'long' => $csv[7],
                'datum' => $csv[8]
            ))
        );
    } else {
        $listado[$code]['fichas'][$md5]['puntos'][] = array(
            'mgrs' => $csv[2],
            'fuso' => $csv[3],
            'utm_x' => $csv[4],
            'utm_y' => $csv[5],
            'lat' => $csv[6],
            'long' => $csv[7],
            'datum' => $csv[8]
        );
    }
}

$especiesErroneas = array();
$especiesDudosas = array();

foreach ($listado as $fila) {
    $nome = '+'.implode(' +', explodeTrim(' ', mysql_real_escape_string($fila['nome'])));

    $query = 'SELECT `id`, `nome`, MATCH(`nome`, `sinonimos`) AGAINST ("'.$nome.'" IN BOOLEAN MODE) AS `puntos` FROM `especies`'
        .' WHERE MATCH(`nome`, `sinonimos`) AGAINST ("'.$nome.'" IN BOOLEAN MODE);';

    $especie = $Db->queryResult($query);

    if (empty($especie)) {
        $nome = explodeTrim(' ', $nome);

        $query = 'SELECT `id`, `nome`, MATCH(`nome`, `sinonimos`) AGAINST ("'.$nome[0].'" IN BOOLEAN MODE) AS `puntos` FROM `especies`'
            .' WHERE MATCH(`nome`, `sinonimos`) AGAINST ("'.$nome[0].'" IN BOOLEAN MODE);';

        $especie = $Db->queryResult($query);
    }

    $fila['checksum'] = md5(serialize($fila));

    $fila['exists'] = $Db->select(array(
        'table' => 'avistamentos',
        'fields' => 'url',
        'limit' => 1,
        'conditions' => array(
            'checksum' => $fila['checksum']
        )
    ));

    if (empty($especie)) {
        $fila['status'] = 'error';
        $fila['message'] = __('Non se atopou a especie indicada na primeira columna.');

        $resultado[] = $fila;
        $especiesErroneas[] = $fila['nome'];
        continue;
    }

    if (count($especie) > 1) {
        $fila['status'] = 'warning';
        $fila['message'] = __('Atopamos máis dunha especie que coincida coa que nos indicas, por favor, selecciona a correcta.');
        
        $posibles = array();
        foreach ($especie as $cada) {
            $fila['especie'] = $cada;
            $resultado[] = $fila;
            $posibles[] = $cada['nome'];
        }

        $especiesDudosas[] = array('nome' => $fila['nome'], 'posibles' => $posibles);;

        continue;
    }

    $fila['status'] = 'success';
    $fila['message'] = __('Confirma que a especie que atopamos na base de datos é a mesma que nos indicas no teu listado.');
    $fila['especie'] = $especie[0];

    $resultado[] = $fila;
}
