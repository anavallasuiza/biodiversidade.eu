<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$config['session'] = array(
	'regular' => array(
		'table' => 'usuarios',
		'name' => 'session-regular',
		'maintain_time' => 3600 * 24 * 10,
		'encrypt' => 'md5',
		'allow_duplicates' => true,

		'id_field' => 'id',
		'user_field' => 'usuario',
		'username_field' => 'nome',
		'password_field' => 'contrasinal',
		'password_tmp_field' => 'contrasinal_tmp',
		'enabled_field' => 'activo',
		'avatar_field' => 'avatar',
		'signup_date_field' => 'data_alta',
		'unsubscribe_field' => 'baixa',
		'unsubscribe_date_field' => 'data_baixa',

		'fields' => array(
			'usuario' => 'usuario',
			'contrasinal' => 'contrasinal',
			'nome' => 'nome',
			'apelido1' => 'apelido1',
			'apelido2' => 'apelido2',
			'nome_completo' => 'nome_completo',
            'especialidade' => 'especialidade',
			'bio' => 'bio',
			'facebook' => 'facebook',
			'twitter' => 'twitter',
			'linkedin' => 'linkedin',
			'avatar' => 'avatar',
			'notificacions' => 'notificacions',
			'notificacions_editor' => 'notificacions_editor'
		)
	)
);
