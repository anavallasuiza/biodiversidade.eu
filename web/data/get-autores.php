<?php 
defined('ANS') or die();

function engadirAutores($autores, &$anteriores) {
    
    foreach($autores as $autor) {
        if ($autor['autor']) {
            $autor = array('id' => $autor['autor'], 'label' => $autor['autor'], 'value' => $autor['autor']);
            
            if (!in_array($autor, $anteriores)) {
                $anteriores[] = $autor;
            }
        }
    }
}

$q = '%'.strip_tags($Vars->var['term']).'%';
$autores = array();

$autoresEspecie = $Db->select(array(
    'table' => 'especies',
    'fields' => 'autor',
    'group' => 'autor', 
    'conditions' => array(
        'autor LIKE' => $q
    )
));

engadirAutores($autoresEspecie, $autores);

$autoresSubespecie = $Db->select(array(
    'table' => 'especies',
    'fields' => 'subespecie_autor',
    'group' => 'subespecie_autor', 
    'conditions' => array(
        'subespecie_autor LIKE' => $q
    )
));

engadirAutores($autoresSubespecie, $autores);

$autoresVariedade = $Db->select(array(
    'table' => 'especies',
    'fields' => 'variedade_autor',
    'group' => 'variedade_autor', 
    'conditions' => array(
        'variedade_autor LIKE' => $q
    )
));

engadirAutores($autoresVariedade, $autores);

usort($autores, function ($a, $b) {
    if ($a['id'] == $b['id']) {
        return 0;
    }

    return ($a['id'] < $b['id']) ? -1 : 1;
});

die(json_encode($autores));
