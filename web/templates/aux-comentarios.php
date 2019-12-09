<?php defined('ANS') or die(); ?>

<section class="comentarios subcontent clear">
	<?php if ($user) { ?>
	<form action="<?php echo path(); ?>" method="post">
		<?php
		echo $Html->img(array(
			'src' => $user['avatar'],
			'alt' => $user['nome']['title'],
			'width' => 50,
			'height' => 50,
			'transform' => 'zoomCrop,50,50',
			'class' => 'usuario'
		));
		?>
		
		<fieldset>
			<label><?php __e('%s, queres deixar un comentario?', $user['nome']['title']); ?>
				<textarea name="texto"></textarea>
			</label>

			<button type="submit" name="phpcan_action" value="comentar" class="btn"><?php __e('Comentar'); ?></button>
		</fieldset>
	</form>
	<?php } ?>

	<?php if ($comentarios) { ?>

	<header>
		<h1><?php echo __('Comentarios deixados:'); ?></h1>
	</header>

	<ul class="listaxe">
		<?php
		foreach ($comentarios as $comentario) {
			$Templates->render('aux-comentario.php', array(
				'comentario' => $comentario
			));
		}
		?>
	</ul>

	<?php } ?>
</section>
