<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$config['cache'] = array(
	'types' => array(
		'api' => array(
			'expire' => 0, // 360
			'interface' => 'files'
		),
		'config' => array(
			'expire' => 3600 * 24 * 30,
			'interface' => 'memcache'
		),
		'db' => array(
			'expire' => 0, // 60
			'interface' => 'memcache'
		),
		'css' => array(
			'expire' => 3600 * 24 * 30,
			'interface' => 'files',
			'folder' => filePath('phpcan/cache|css'),
			'minify' => false,
			'compress' => false,
			'pack' => false
		),
		'data' => array(
			'expire' => 0, // 600
			'interface' => 'memcache'
		),
		'default' => array(
			'expire' => 0, // 600
			'interface' => 'memcache'
		),
		'images' => array(
			'expire' => 3600 * 24 * 30,
			'interface' => 'files',
			'folder' => filePath('phpcan/cache|images')
		),
		'js' => array(
			'expire' => 3600 * 24 * 30,
			'interface' => 'files',
			'folder' => filePath('phpcan/cache|js'),
			'minify' => false,
			'compress' => false,
			'pack' => false
		),
		'templates' => array(
			'expire' => 0, // 600
			'interface' => 'memcache'
		)
	),
	'memcached' => array(
		'host' => 'localhost',
		'port' => 11211
	),
	'headers_no_cache' => true, // false
);
