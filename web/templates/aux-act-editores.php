<?php
defined('ANS') or die();

if (empty($Config->tables2routes[$log['related_table']])) {
	return '';
}

$route = $Config->tables2routes[$log['related_table']];
$related = $log[$log['related_table']];

if (empty($related) && !isset($ocultar_contido)) {
	return '';
} else if (!empty($related)) {
	$link = path($route['route'], $related[$route['url']]);
	$title = $related['titulo'] ?: $related['nome'];
}
?>

<li>
	<article class="act-contido">
		<i class="icon-edit-sign icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						if ($ocultar_contido) {
							__e('%s actualizou a lista de editores', $autor);
						} else {
							__e('Actualizaches a lista de editores de <a href="%s">%s</a>', $link, $title);
						}
					} else {
						if ($amosar_usuario) {
							__e('%s actualizou a lista de editores de <a href="%s">%s</a>', $autor, $link, $title);
						} else {
							__e('Actualizou a lista de editores de <a href="%s">%s</a>', $link, $title);
						}
					}
				?></p>
			</div>
		</header>
	</article>
</li>
