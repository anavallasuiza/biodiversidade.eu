<?php
defined('ANS') or die();

if ($user && (($user['id'] == $comentario['usuarios_autor']['id']) || $Acl->check('action', 'comentario-eliminar-all'))) {
	$borrar = true;
} else {
	$borrar = false;
}
?>

<li>
	<article class="comentario">
		<?php if ($borrar) { ?>
		<div class="comentario-borrar">
			<a href="?phpcan_action=comentario-eliminar&amp;id=<?php echo $comentario['id']; ?>" data-confirm="<?php __e('¿Estás eguro de que desexa borrar este comentario?'); ?>">
				<i class="icon-trash"></i>
				<span><?php __e('Borrar comentario'); ?></span>
			</a>
		</div>
		<?php } ?>

		<?php
		echo $Html->img(array(
			'src' => $comentario['usuarios_autor']['avatar'],
			'alt' => $comentario['usuarios_autor']['nome']['title'],
			'width' => 50,
			'height' => 50,
			'transform' => 'zoomCrop,50,50',
			'class' => 'usuario'
		));
		?>

		<footer class="comentario-info">
			<a href="<?php echo path('perfil', $comentario['usuarios_autor']['nome']['url']); ?>" class="comentario-autor"><?php echo $comentario['usuarios_autor']['nome']['title'].' '.$comentario['usuarios_autor']['apelido1']; ?></a>
			<?php echo ucfirst($Html->time($comentario['data'])); ?>
		</footer>

		<div class="comentario-contido">
			<?php echo $comentario['texto']; ?>
		</div>
	</article>
</li>
