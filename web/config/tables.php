<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$config['tables'] = array(
    'default' => array(
        'adxuntos' => array(
            'titulo' => array(
                'format' => 'varchar',
                'required' => true
            ),
            'arquivo' => array(
                'format' => 'file',
                'images' => array(
                    'transform' => 'resize,1600,1600'
                )
            ),
            'ligazon' => 'url'
        ),

        'ameazas' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all',
                'required' => true
            ),
            'lugar' => 'varchar',
            'ligazon' => 'url',
            'data' => 'date',
            'data_alta' => 'datetime',
            'nivel' => array(
                'format' => 'enum',
                'values' => array('', '1', '2', '3')
            ),
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'kml' => 'file',
            'notificada' => 'boolean',
            'estado' => 'boolean',
            'data_observacions' => 'datetime',
            'activo' => 'boolean'
        ),

        'ameazas_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'orde' => 'sort',
            'activo' => 'boolean'
        ),

        'avistamentos' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'required' => true
            ),
            'posible' => 'varchar',
            'data_observacion' => 'datetime',
            'localidade' => 'varchar',
            'nome_zona' => 'varchar',
            'outros_observadores' => 'varchar',
            'autoctona' => 'boolean',
            'aloctona' => 'boolean',
            'invasora' => 'boolean',
            'idcoleccion' => 'varchar',
            'tipo_referencia' => array(
                'format' => 'enum',
                'values' => array('', 'observacion', 'bibliografico', 'coleccion', 'outros')
            ),
            'referencia' => 'text',
            'colector' => 'text',
            'observacions' => 'text',

            'substrato_xeoloxico' => 'varchar',
            'uso_solo' => 'varchar',
            'uso_activo' => 'boolean',
            'estado_conservacion' => array(
                'format' => 'enum',
                'values' => array('', 'degradado', 'normal', 'conservado')
            ),
            'abundancia' => array(
                'format' => 'enum',
                'values' => array('', 'baixa', 'media', 'alta')
            ),
            'distribucion' => array(
                'format' => 'enum',
                'values' => array('', 'dispersa', 'media', 'localizada')
            ),
            'xestion_ambiental' => array(
                'format' => 'enum',
                'values' => array('', 'activa', 'pasiva', 'inexistente')
            ),
            'observacions_conservacion' => 'text',

            // XERMOPLASMA

            'banco_xeoplasma' => 'text',

            'numero_colleita' => 'varchar',
            'xermoplasma_herbario' => 'varchar',
            'xermoplasma_herbario_numero' => 'varchar',
            'profundidade_auga' => 'integer',
            'pendente' => 'integer',
            'orientacion' => 'varchar',
            'metodo_muestreo' => array(
                'format' => 'enum',
                'values' => array('', 'aleatorio', 'regular', 'transecto', 'centro_poboacion', 'periferia_poboacion', 'outro')
            ),
            'adultas_localizadas' => 'integer',
            'adultas_mostreadas' => 'integer',
            'area_ocupacion' => 'integer',
            'area_mostreada' => 'integer',
            'arquivo_gps' => 'file',
            'fenoloxia_individuos' => array(
                'format' => 'enum',
                'values' => array('', 'plantula', 'vexetativo', 'flor', 'froito')
            ),
            'fenoloxia_froito' => array(
                'format' => 'enum',
                'values' => array('', 'pasado', 'optimo', 'inmaduro')
            ),
            'procedencia_semente' => array(
                'format' => 'enum',
                'values' => array('', 'planta', 'solo', 'ambas')
            ),
            'tipo_vexetacion' => array(
                'format' => 'enum',
                'values' => array('', 'herbaceo', 'mato', 'forestal', 'cantil', 'duna', 'marisma', 'curso-fluvial', 'lagoa/charca/encoro', 'outro')
            ),
            'textura_solo' => array(
                'format' => 'enum',
                'values' => array('', 'cascallo', 'area', 'franco-areoso', 'franco', 'franco-arxiloso', 'turba', 'sen-solo')
            ),
            'sexo' => array(
                'format' => 'enum',
                'values' => array('', 'macho', 'femia')
            ),
            'fase' => array(
                'format' => 'enum',
                'values' => array('', 'ovo', 'larva', 'ninfa', 'cria', 'adulto', 'outros')
            ),
            'migracion' => array(
                'format' => 'enum',
                'values' => array('', 'invernante', 'pasaxe', 'estivais', 'residentes')
            ),
            'climatoloxia' => 'text',
            'observacions_xermoplasma' => 'text',

            // COROLOXIA

            'distancia_umbral' => 'varchar',
            'definicion_individuo' => 'varchar',
            'coroloxia_herbario' => 'varchar',
            'coroloxia_herbario_numero' => 'varchar',
            'numero_exemplares' => 'integer',
            'area_presencia_shapefile' => 'file',
            'tipo_censo' => array(
                'format' => 'enum',
                'values' => array('', 'directo', 'estima')
            ),
            'superficie_ocupacion' => 'integer',
            'densidade_ocupacion' => 'integer',
            'area_prioritaria' => 'file',
            'area_potencial' => 'file',
            'superficie_mostreada' => 'integer',
            'individuos_contados' => 'integer',
            'superficie_potencial' => 'integer',
            'densidade' => 'integer',
            'individuos_estimados' => 'integer',
            'observacions_coroloxia' => 'text',

            'validado' => 'boolean',
            'checksum' => 'varchar',
            'data_alta' => 'datetime',
            'activo' => 'boolean'
        ),

        'banners' => array(
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'ligazon' => 'varchar',
            'orde' => 'integer',
            'activo' => 'boolean'
        ),

        'backups' => array(
            'related_table' => 'varchar',
            'related_id' => 'integer',
            'date' => 'datetime',
            'ip' => 'ip',
            'action' => 'varchar',
            'checksum' => 'varchar',
            'content' => 'text'
        ),

        'blogs' => array(
            'url' => 'id_text',
            'titulo' => 'varchar',
            'texto' => 'html',
            'imaxe' => array(
                'format' => 'image',
                'transform' => 'resize,1600,1600'
            ),
            'data' => 'datetime',
            'data_actualizado' => 'datetime',
            'activo' => 'boolean'
        ),

        'blogs_posts' => array(
            'url' => 'id_text',
            'titulo' => 'varchar',
            'texto' => 'html',
            'data' => 'datetime',
            'activo' => 'boolean'
        ),

        'cadernos' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'required' => true
            ),
            'texto' => 'html',
            'data_alta' => 'datetime',
            'data_actualizado' => 'datetime',
            'activo' => 'boolean'
        ),

        'clases' => array(
            'url' => 'id_text',
            'nome' => 'varchar'
        ),

        'comentarios' => array(
            'texto' => array(
                'format' => 'text',
                'required' => true
            ),
            'data' => 'datetime',
            'orde' => 'integer',
            'activo' => 'boolean'
        ),

        'comunidade' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'logo' => array(
                'format' => 'image',
                'transform' => 'resize,500,500'
            ),
            'web' => 'url',
            'correo' => 'email',
            'telefono' => 'varchar',
            'data_alta' => 'datetime',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'activo' => 'boolean'
        ),

        'concellos' => array(
            'nome' => 'title'
        ),

        'conservacion' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'required' => true,
            ),
            'texto' => array(
                'format' => 'html',
                'required' => true
            ),
            'data' => 'datetime',
            'activo' => 'boolean'
        ),

        'conservacion_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'datums' => array(
            'url' => 'id_text',
            'nome' => 'varchar',
            'radius' => 'integer',
            'square_eccentricity' => array(
                'format' => 'float',
                'length_max' => '11,9',
                'unsigned' => false,
                'default' => '0.000000000'
            ),
            'flattering' => array(
                'format' => 'float',
                'length_max' => '12,8',
                'unsigned' => false,
                'default' => '0.000000000'
            ),
            'orde' => 'sort',
            'activo' => 'boolean'
        ),

        'didacticas' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'intro' => array(
                'format' => 'text',
                'languages' => 'all'
            ),
            'xustificacion' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'desenvolvemento' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'obxectivos' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'competencias' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'duracion' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'material' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'recursos' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'data' => 'datetime',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'activo' => 'boolean'
        ),

        'documentacion' => array(
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'texto' => 'html',
            'arquivo' => array(
                'format' => 'file',
                'image' => array(
                    'transform' => 'resize,1600,1600'
                )
            ),
            'data' => 'datetime',
            'activo' => 'boolean'
        ),

        'documentacion_grupos' => array(
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            )
        ),

        'equipos' => array(
            'url' => 'id_text',
            'titulo' => 'varchar',
            'texto' => 'html',
            'imaxe' => array(
                'format' => 'image',
                'transform' => 'resize,800,800'
            ),
            'data' => 'datetime',
            'activo' => 'boolean'
        ),

        'espazos' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all',
                'required' => true
            ),
            'data' => 'datetime',
            'lugar' => 'varchar',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'kml' => 'file',
            'validado' => 'boolean',
            'data_observacions' => 'datetime',
            'activo' => 'boolean'
        ),

        'espazos_figuras' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'espazos_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'especies' => array(
            'url' => 'id_text',
            'nome' => array( // Portugaliza-COL
                'format' => 'varchar',
                'fulltext' => 'fulltext',
            ),
            'nome_cientifico' => 'varchar',
            'autor' => 'varchar', // 1) Col Autor 2) Aut_Espec
            'subespecie' => 'varchar',
            'subespecie_autor' => 'varchar',
            'variedade' => 'varchar',
            'variedade_autor' => 'varchar',
            'nome_comun' => 'varchar',
            'sinonimos' => 'text',
            'lsid_name' => 'varchar',
            'aloctona' => 'boolean',
            'invasora' => 'boolean',
            'protexida' => 'boolean',
            'especie' => 'varchar',
            'taxon_id' => 'integer',
            'nivel_ameaza' => 'integer',
            'descricion' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'cromosomas' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'fenoloxia' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'distribucion' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'habitat' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'poboacion' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'ameazas' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'conservacion' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'agradecementos' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'observacions' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'bibliografia' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'data_alta' => 'datetime',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'validada' => 'boolean',
            'bloqueada' => 'boolean',
            'activo' => 'boolean'
        ),

        'eventos' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'lugar' => 'varchar',
            'data' => 'datetime',
            'data_alta' => 'datetime',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'activo' => 'boolean'
        ),

        'formas' => array(
            'url' => 'id_text',
            'tipo' => array(
                'format' => 'enum',
                'values' => array('', 'polyline', 'polygon')
            )
        ),

        'familias' => array(
            'url' => 'id_text',
            'nome' => 'varchar'
        ),

        'filos' => array(
            'url' => 'id_text',
            'nome' => 'varchar'
        ),

        'grupos' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'habitats_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'imaxes' => array(
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'imaxe' => array(
                'format' => 'image',
                'transform' => 'resize,1600,1600'
            ),
            'portada' => 'boolean',
            'licenza' => array(
                'format' => 'enum',
                'values' => array('', '©', 'CC BY', 'CC BY-SA', 'CC BY-ND', 'CC BY-NC', 'CC BY-NC-SA', 'CC BY-NC-ND')
            ),
            'autor' => 'varchar',
            'activo' => 'boolean'
        ),

        'imaxes_tipos' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'importacions' => array(
            'url' => 'id_text',
            'data' => 'datetime',
            'activo' => 'boolean'
        ),

        'iniciativas' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all',
                'required' => true
            ),
            'lugar' => 'varchar',
            'ligazon' => 'url',
            'data' => 'datetime',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'kml' => 'file',
            'estado' => 'boolean',
            'data_observacions' => 'datetime',
            'activo' => 'boolean'
        ),

        'iniciativas_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'orde' => 'sort',
            'activo' => 'boolean'
        ),

        'habitats_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'activo' => 'boolean'
        ),

        'logs' => array(
            'related_table' => 'varchar',
            'related_id' => 'integer',
            'related_table2' => 'varchar',
            'related_id2' => 'integer',
            'date' => 'datetime',
            'ip' => 'ip',
            'action' => 'varchar',
            'public' => 'boolean'
        ),

        'notas' => array(
            'url' => 'id_text',
            'codigo' => 'id_text',
            'titulo' => 'varchar',
            'texto' => 'text',
            'data' => 'datetime',
            'tipo' => 'varchar'
        ),

        'novas' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'data' => 'datetime',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'activo' => 'boolean'
        ),

        'opcions' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'activo' => 'boolean'
        ),

        'opcions_categorias' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'orde' => 'integer',
            'nivel' => array(
                'format' => 'enum',
                'values' => array('', '1', '2', '3')
            ),
            'multiple' => 'boolean',
            'activo' => 'boolean'
        ),

        'ordes' => array(
            'url' => 'id_text',
            'nome' => 'varchar'
        ),

        'paises' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'email' => 'email',
            'telefono' => 'varchar'
        ),

        'permissions' => array(
            'name' => array(
                'format' => 'varchar',
                'required' => true
            ),
            'code' => array(
                'format' => 'varchar',
                'required' => true,
                'unique' => 'code'
            ),
            'mode' => array(
                'format' => 'enum',
                'values' => array('', 'controller', 'action'),
                'required' => true
            ),
            'enabled' => 'boolean',
        ),

        'provincias' => array(
            'nome' => 'title'
        ),

        'proxectos' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'required' => true
            ),
            'intro' => 'html',
            'texto' => 'html',
            'data' => 'datetime',
            'aberto' => 'boolean',
            'activo' => 'boolean'
        ),

        'puntos' => array(
            'titulo' => 'varchar',
            'datum' => 'varchar',
            'arquivo' => 'file',
            'localidade' => 'varchar',
            'mgrs' => 'varchar',
            'utm_fuso' => 'varchar',
			'utm_sur' => 'boolean',
            'utm_x' => 'integer',
            'utm_y' => 'integer',
            'll' => 'varchar',
            'latitude' => array(
                'format' => 'float',
                'length_max' => '16,14',
                'unsigned' => false,
                'default' => '0.00000000000000'
            ),
            'lonxitude' => array(
                'format' => 'float',
                'length_max' => '16,14',
                'unsigned' => false,
                'default' => '0.00000000000000'
            ),
            'altitude' => array(
                'format' => 'integer',
                'unsigned' => false
            ),
            'tipo' => array(
                'format' => 'enum',
                'values' => array('', 'latlong', 'utm', 'mgrs', 'file')
            ),
            'procesado' => 'boolean',
            'so_altitude' => 'boolean', // Puntos de rotas para almacenar a altura da mesme
            'orde' => 'integer'
        ),

        'puntos_tipos' => array(
            'numero' => 'integer',
            'nome' => 'varchar'
        ),

        'pois' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true
            ),
            'texto' => array(
                'format' => 'text',
                'languages' => 'all'
            ),
            'latitude' => array(
                'format' => 'float',
                'length_max' => '16,14',
                'unsigned' => false,
                'default' => '0.00000000000000'
            ),
            'lonxitude' => array(
                'format' => 'float',
                'length_max' => '16,14',
                'unsigned' => false,
                'default' => '0.00000000000000'
            ),
            'activo' => 'boolean'
        ),

        'pois_tipos' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true
            ),
            'imaxe' => 'image',
            'activo' => 'boolean'
        ),

        'proteccions_tipos' => array(
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true
            ),
            'activo' => 'boolean'
        ),

        'referencias_tipos' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all'
            )
        ),

        'reinos' => array(
            'url' => 'id_text',
            'nome' => 'varchar',
            'activo' => 'boolean'
        ),

        'roles' => array(
            'name' => array(
                'format' => 'varchar',
                'required' => true
            ),
            'code' => array(
                'format' => 'varchar',
                'required' => true,
                'unique' => 'code'
            ),
            'enabled' => 'boolean'
        ),

        'rotas' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all',
                'required' => true,
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all',
                'required' => true
            ),
            'lugar' => 'varchar',
            'area' => 'varchar',
            'dificultade' => array(
                'format' => 'enum',
                'values' => array('', 'alta', 'media', 'baixa')
            ),
            'distancia' => 'integer',
            'duracion' => 'integer',
            'data' => 'datetime',
            'validado' => 'boolean',
            'votos_contador' => 'integer',
            'votos_valoracion' => 'integer',
            'votos_media' => 'float',
            'kml' => 'file',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'data_observacions' => 'datetime',
            'activo' => 'boolean'
        ),

        'sessions' => array(
            'date' => 'datetime',
            'ip' => 'ip',
            'action' => 'varchar',
            'post_content' => 'text',
            'session_content' => 'text',
            'successfully' => 'boolean'
        ),

        'territorios' => array(
            'url' => 'id_text',
            'nome' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'email' => 'email',
            'telefono' => 'varchar',
            'orde' => 'sort',
            'activo' => 'boolean'
        ),

        'textos' => array(
            'url' => 'id_text',
            'titulo' => array(
                'format' => 'varchar',
                'languages' => 'all'
            ),
            'texto' => array(
                'format' => 'html',
                'languages' => 'all'
            ),
            'imaxe' => array(
                'format' => 'image',
                'transform' => 'resize,1600'
            ),
            'orde' => 'integer',
            'menu' => 'varchar',
            'idioma' => array(
                'format' => 'enum',
                'values' => array('', 'gl', 'es', 'pt', 'en'),
                'required' => true,
                'null' => false
            ),
            'activo' => 'boolean'
        ),

        'usuarios' => array(
            'usuario' => array(
                'format' => 'email',
                'index' => 'usuario',
                'required' => true
            ),
            'contrasinal' => array(
                'format' => 'password',
                'encrypt' => 'md5'
            ),
            'contrasinal_tmp' => 'varchar',
            'nome' => array(
                'format' => 'title',
                'required' => true
            ),
            'apelido1' => 'varchar',
            'apelido2' => 'varchar',
            'nome_completo' => 'varchar',
            'bio' => 'text',
            'linkedin' => 'varchar',
            'twitter' => 'varchar',
            'facebook' => 'varchar',
            'publico' => 'boolean',
            'avatar' => array(
                'format' => 'image',
                'default' => 'usuarios/avatar/default.png',
                'transform' => 'resize,800,800',
                'valid_extensions' => array('jpg','jpeg','gif','png')
            ),
            'notificacions' => array(
                'format' => 'boolean',
                'default' => 1
            ),
            'notificacions_editor' => 'boolean',
            'data_alta' => 'datetime',
            'baixa' => 'boolean',
            'data_baixa' => 'datetime',
            'solicita_importar' => 'boolean',
            'especialidade' => 'varchar',
            'activo' => 'boolean'
        ),

        'votos' => array(
            'ip' => 'ip',
            'data' => 'datetime',
            'voto' => 'integer'
        ),

        'xeneros' => array(
            'url' => 'id_text',
            'nome' => 'varchar'
        )
    )
);

$config['relations'] = array(
    'default' => array(
        array(
            'tables' => 'adxuntos avistamentos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos ameazas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos conservacion',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos didacticas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos eventos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos iniciativas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos novas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'adxuntos proxectos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'ameazas ameazas_tipos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas avistamentos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas backups',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'ameazas comentarios',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'ameazas concellos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas espazos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas especies',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas formas',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'ameazas imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'ameazas logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'ameazas proxectos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas provincias',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas paises',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas puntos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas territorios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'ameazas usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas_tipos avistamentos',
            'name' => 'nivel1',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'ameazas_tipos avistamentos',
            'name' => 'nivel2',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos backups',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'avistamentos comentarios',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'avistamentos concellos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos espazos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos especies',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'avistamentos especies',
            'name' => 'acompanhantes',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos grupos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'avistamentos iniciativas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos habitats_tipos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'avistamentos imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'avistamentos logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'avistamentos opcions',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos provincias',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos paises',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos territorios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos puntos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'avistamentos proxectos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos referencias_tipos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos rotas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'avistamentos usuarios',
            'name' => 'observadores',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'avistamentos usuarios',
            'name' => 'validador',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'avistamentos usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'backups blogs',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups blogs_posts',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups cadernos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups didacticas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups espazos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups especies',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups eventos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups equipos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups iniciativas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups comunidade',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups novas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups proxectos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups rotas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups usuarios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'backups usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'blogs blogs_posts',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'blogs logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'blogs proxectos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'blogs usuarios',
            'name' => 'autor',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'blogs usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'blogs_posts comentarios',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'blogs_posts imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'blogs_posts logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'blogs_posts usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'cadernos comentarios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'cadernos imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'cadernos logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'cadernos usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'cadernos proxectos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'clases especies',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'clases familias',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'clases filos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'clases grupos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'clases xeneros',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'clases ordes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'clases reinos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios conservacion',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios espazos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios especies',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios eventos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios iniciativas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'comentarios novas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios proxectos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios rotas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comentarios usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'comunidade logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'comunidade usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'concellos conservacions',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'concellos iniciativas',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'concellos provincias',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'concellos puntos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'concellos rotas',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'conservacion conservacion_tipos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'conservacion especies',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'conservacion usuarios',
            'nome' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'didacticas logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'didacticas usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'documentacion documentacion_grupos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'espazos espazos_figuras',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'espazos espazos_tipos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'espazos imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'espazos iniciativas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'espazos especies',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'espazos logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'espazos pois',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'espazos puntos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'espazos proxectos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'espazos territorios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'espazos usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'espazos usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'espazos usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'especies especies',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'especies familias',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies filos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies grupos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'especies iniciativas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'especies logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'especies ordes',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies proteccions_tipos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'especies proxectos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'especies reinos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies rotas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'especies usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies usuarios',
            'name' => 'validador',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'especies usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'especies xeneros',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'eventos imaxes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'eventos logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'eventos usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'familias filos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'familias grupos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'familias ordes',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'familias reinos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'familias xeneros',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'filos grupos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'filos ordes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'filos reinos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'filos xeneros',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'formas espazos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'formas iniciativas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'formas puntos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'formas rotas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'equipos logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'equipos usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'equipos usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'equipos usuarios',
            'name' => 'solicitudes',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'grupos ordes',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'grupos reinos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'grupos xeneros',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'imaxes espazos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'imaxes imaxes_tipos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'imaxes iniciativas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'imaxes novas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'imaxes proxectos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'imaxes rotas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'imaxes textos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'imaxes_tipos reinos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'importacions avistamentos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'importacions especies',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'importacions puntos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'importacions usuarios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'iniciativas iniciativas_tipos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas logs',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'iniciativas proxectos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas provincias',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas paises',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas puntos',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas territorios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'iniciativas usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'iniciativas usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'logs novas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'logs proxectos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'logs rotas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'logs usuarios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'logs usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'notas puntos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'notas usuarios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'novas usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'opcions opcions_categorias',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'ordes reinos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'ordes xeneros',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'paises provincias',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'paises rotas',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'permissions roles',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'pois pois_tipos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'provincias rotas',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'proxectos rotas',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'proxectos usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'proxectos usuarios',
            'name' => 'solicitude',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'proxectos usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'puntos datums',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'puntos puntos_tipos',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'puntos rotas',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'reinos xeneros',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'roles usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'rotas pois',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'rotas territorios',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'rotas usuarios',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'rotas usuarios',
            'name' => 'autor',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'rotas usuarios',
            'name' => 'validador',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'rotas usuarios',
            'name' => 'vixiar',
            'mode' => 'x x'
        ),

        array(
            'tables' => 'rotas votos',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'territorios paises',
            'mode' => 'x 1'
        ),

        array(
            'tables' => 'territorios provincias',
            'mode' => '1 x'
        ),

        array(
            'tables' => 'usuarios votos',
            'mode' => '1 x'
        )
    )
);

$config['tables_ignored'] = array(
    'default' => array('gis_lines', 'gis_points', 'gis_polygons')
);
