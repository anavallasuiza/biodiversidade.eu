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

$link = path($route['route'], $related[$route['url']]);
$title = $related['titulo'] ?: $related['nome'];
?>

<li>
	<article class="act-contido">
		<i class="icon-pencil icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						__e('Validaches <a href="%s">%s</a>', $link, $title);
					} else {
						if ($amosar_usuario) {
							__e('%s validou <a href="%s">%s</a>', $autor, $link, $title);
						} else {
							__e('Validou <a href="%s">%s</a>', $link, $title);
						}
					}
				?></p>
			</div>
		</header>
	</article>
</li>
