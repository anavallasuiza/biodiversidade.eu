<?php defined('ANS') or die(); ?>

<li>
	<article class="espazo">
		<?php
		if ($espazo['imaxe']) {
			echo $Html->img(array(
				'src' => $espazo['imaxe']['imaxe'],
				'alt' => $espazo['titulo'],
				'width' => 150,
				'height' => 150,
				'transform' => 'zoomCrop,150,150',
				'class' => 'espazo-miniatura'
			));
		}
		?>

		<header>
			<h1>
				<a href="<?php echo path('espazo', $espazo['url']); ?>"><?php echo $espazo['titulo']; ?></a>

				<?php if ($espazo['validado']) { ?>
				<span class="estado solucionada"><i class="icon-thumbs-up"></i> <?php __e('Validado'); ?></span>
				<?php } else { ?>
				<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validado'); ?></span>
				<?php } ?>
			</h1>

			<ul class="espazo-informacion">
				<?php if ($espazo['territorios']) { ?><li><strong><?php echo $espazo['territorios']['nome']; ?></strong></li><?php } ?>
				<?php if ($espazo['espazos_figuras']) { ?><li><?php __e('Figura de protección'); ?>: <strong><?php echo $espazo['espazos_figuras']['nome']; ?></strong></li><?php } ?>
				<?php if ($espazo['espazos_tipos']) { ?><li><?php __e('Tipo'); ?>: <strong><?php echo $espazo['espazos_tipos']['nome']; ?></strong></li><?php } ?>
				<?php if ($espazo['comentarios']) { ?><li><?php echo (count($espazo['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($espazo['comentarios'])); ?></li><?php } ?>
			</ul>

			<?php if ($espazo['especies']) { ?>
			<section class="listaxe-relacionada">
				<header>
					<h1><?php __e('Especies'); ?>:</h1>
				</header>
				<ul class="espazo-especies">
					<?php $especies = array_chunk($espazo['especies'], ceil(count($espazo['especies']) / 2)); ?>
					<li>
						<ul>
							<?php foreach ($especies[0] as $especie) { ?>
							<li><a href="<?php echo path('especie', $especie['url']); ?>"><?php echo $especie['nome']; ?></a></li>
							<?php } ?>
						</ul>
					</li>

					<?php if (count($especies[1])) { ?>
					<li>
						<ul>
							<?php foreach ($especies[1] as $especie) { ?>
							<li><a href="<?php echo path('especie', $especie['url']); ?>"><?php echo $especie['nome']; ?></a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</section>
			<?php } ?>
		</header>
	</article>
</li>
