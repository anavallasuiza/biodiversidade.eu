<?php
defined('ANS') or die();

if (empty($Config->tables2routes[$log['related_table']])) {
	return '';
}

$title = preg_replace('/s$/', '', $log['related_table']);
$pre = (substr($title, -1) === 'a') ? __('unha') : __('un');
?>

<li>
	<article class="act-contido">
		<i class="icon-trash icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						__e('Eliminaches %s %s', $pre, $title);
					} else {
						if ($amosar_usuario) {
							__e('%s eliminou %s %s', $autor, $pre, $title);
						} else {
							__e('Eliminou %s %s', $pre, $title);
						}
					}
				?></p>
			</div>
		</header>
	</article>
</li>
