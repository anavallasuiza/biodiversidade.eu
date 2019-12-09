<?php
defined('ANS') or die();

if (empty($table) || empty($id) || empty($imaxes) || empty($imaxes['imaxe'])) {
    return false;
}

if (count($imaxes['imaxe']) > 1) {
    foreach ($imaxes['imaxe'] as $i => $imaxe) {
        $licenza = $imaxes['licenza'][$i];
        $tipo = $imaxes['tipo'][$i];
        $portada = $imaxes['main'][$i] ? true : false;
        $autor = $imaxes['autor'][$i];

        if (empty($licenza)) {
            continue;
        }

        $query = array(
            'table' => 'imaxes',
            'data' => array(
                'licenza' => $licenza,
                'portada' => $portada,
                'autor' => $autor,
                'activo' => 1
            ),
            'limit' => 1,
            'conditions' => array(
                'id' => $imaxes['id'][$i],
                $table.'.id' => $id
            ),
            'relate' => array(
                array(
                    'table' => $table,
                    'conditions' => array(
                        'id' => $id
                    )
                )
            )
        );

        if ($imaxe['error'] !== 4) {
            $query['data']['imaxe'] = $imaxe;
        }

        if ($tipo) {
            $query['relate'][] = array(
                'table' => 'imaxes_tipos',
                'conditions' => array(
                    'url' => $tipo
                )
            );
        }

        if (empty($imaxes['id'][$i])) {
            if ($imaxe['error'] === 4) {
                continue;
            }

            $action = 'insert';
        } else {
            if ($imaxe['error'] === 4) {
                unset($data['imaxe']);
            }

            $action = 'update';
        }

        $success = $Db->$action($query);

        if (empty($success)) {
            $Vars->message(implode('<br />', $Errors->getList()), 'ko');

            return false;
        }
    }
}

if ($imaxes['borrar']) {
    $Db->delete(array(
        'table' => 'imaxes',
        'conditions' => array(
            'id' => $imaxes['borrar'],
            $table.'.id' => $id
        )
    ));
}

return true;
