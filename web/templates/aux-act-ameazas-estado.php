<?php
defined('ANS') or die();

if (empty($Config->tables2routes[$log['related_table']])) {
	return '';
}

$route = $Config->tables2routes[$log['related_table']];
$related = $log[$log['related_table']];

if (empty($related)) {
	return '';
}

$status = str_replace('ameazas-estado-', '', $log['action']);

$link = path($route['route'], $related[$route['url']]);
$title = $related['titulo'] ?: $related['nome'];
?>

<li>
	<article class="act-contido">
		<i class="icon-edit-sign icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						__e('Marcaches a ameaza <a href="%s">%s</a> como %s', $link, $title, __($status));
					} else {
						if ($amosar_usuario) {
							__e('%s marcou a ameaza <a href="%s">%s</a> como %s', $autor, $link, $title, __($status));
						} else {
							__e('Marcou a ameaza <a href="%s">%s</a> como %s', $link, $title, __($status));
						}
					}
				?></p>
			</div>
		</header>
	</article>
</li>
