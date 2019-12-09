<?php
defined('ANS') or die();

if (empty($Config->tables2routes[$log['related_table']])) {
	return '';
}

$route1 = $Config->tables2routes[$log['related_table']];
$route2 = $Config->tables2routes[$log['related_table2']];

$related1 = $log[$log['related_table']];
$related2 = $log[$log['related_table2']];

if (empty($related1) && !isset($ocultar_contido)) {
	return '';
} else if (!empty($related1)) {
	$link1 = path($route1['route'], $related1[$route1['url']]);
	$title1 = $related1['titulo'] ?: $related1['nome'];
	$link2 = path($route2['route'], $related2[$route2['url']]);
	$title2 = $related2['titulo'] ?: $related2['nome'];
}
?>

<li>
	<article class="act-contido">
		<i class="icon-pencil icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						if ($ocultar_contido) {
							__e('Asociado á especie <a href="%s">%s</a> por %s', $link2, $title2, $autor);
						} else {
							__e('Asociaches a especie <a href="%s">%s</a> para o avistamento <a href="%s">%s</a>', $link2, $title2, $link1, $title1);
						}
					} else {
						if ($amosar_usuario) {
							__e('%s asociou a especie <a href="%s">%s</a> para o avistamento <a href="%s">%s</a>', $autor, $link2, $title2, $link1, $title1);
						} else {
							__e('Asociou a especie <a href="%s">%s</a> para o avistamento <a href="%s">%s</a>', $link2, $title2, $link1, $title1);
						}
					}
				?></p>
			</div>
		</header>
	</article>
</li>
