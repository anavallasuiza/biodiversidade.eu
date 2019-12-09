<?php
defined('ANS') or die();

if (empty($log['comentarios']) || empty($Config->tables2routes[$log['related_table2']])) {
	return '';
}

$route = $Config->tables2routes[$log['related_table2']];
$related = $log[$log['related_table2']];

if (empty($related)) {
	return '';
}

$link = path($route['route'], $related[$route['url']]);
$title = $related['titulo'] ?: $related['nome'];
?>

<li>
	<article class="act-contido">
		<i class="icon-comment icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						__e('Fixeches un comentario en <a href="%s">%s</a>', $link, $title);
					} else {
						if ($amosar_usuario) {
							__e('%s fixo un comentario en <a href="%s">%s</a>', $autor, $link, $title);
						} else {
							__e('Fixo un comentario en <a href="%s">%s</a>', $link, $title);
						}
					}
				?></p>
				
				<div class="activity-details">
				    <?php echo textCutter($log['comentarios']['texto']);?>
				</div>
			</div>
		</header>
	</article>
</li>
