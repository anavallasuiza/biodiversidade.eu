<?php defined('ANS') or die(); ?>

<li>
	<article class="rota">
		<?php
		if ($rota['imaxe']) {
			echo $Html->img(array(
				'src' => $rota['imaxe']['imaxe'],
				'alt' => $rota['titulo'],
				'width' => 150,
				'height' => 150,
				'transform' => 'zoomCrop,150,150',
				'class' => 'rota-miniatura'
			));
		}
		?>

		<header>
			<h1>
                <?php if ($checkbox) { ?>
                <input type="checkbox" name="rotas[id][]" value="<?php echo $rota['id']; ?>" />
                <?php } ?>

				<a href="<?php echo path('rota', $rota['url']); ?>"><?php echo $rota['titulo']; ?></a>

				<?php if ($rota['validado']) { ?>
				<span class="estado solucionada"<?php echo $rota['usuarios_validador'] ? (' title="'.__('Validada por %s', $rota['usuarios_validador']['nome']['title']).'"') : ''; ?>><i class="icon-thumbs-up"></i> <?php __e('Validada'); ?></span>
				<?php } else { ?>
				<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?></span>
				<?php } ?>
			</h1>

			<ul class="rota-informacion">
				<?php if ($rota['territorios']) { ?><li><?php echo $rota['concellos']['nome']['title'].' '.$rota['provincias']['nome']['title'] . ' ' . $espazo['territorios']['nome']; ?></li><?php } ?>
                <?php if ($rota['lugar']) { ?><li><p><strong><?php echo $rota['lugar']; ?></strong></p></li><?php } ?>
				<?php if ($rota['dificultade']) { ?><li><?php __e('Dificultade'); ?>: <strong><?php echo ucfirst($rota['dificultade']); ?></strong></li><?php } ?>
				<li><?php __e('Distancia'); ?>: <strong><?php echo (intval($rota['distancia'] > 1000) ? number_format($rota['distancia'] / 1000, 1, ',', '') . ' Km': $rota['distancia'] . ' m'); ?></strong></li>
				<?php if ($rota['duracion']) { ?><li><?php __e('Duración'); ?>: <strong><?php echo gmdate('H \h i \m', ($rota['duracion'] * 60)); ?></strong></li><?php } ?>
				<?php if ($rota['comentarios']) { ?><li><?php echo (count($rota['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($rota['comentarios'])); ?></li><?php } ?>
			</ul>

			<?php if ($rota['especies']) { ?>
			<section class="listaxe-relacionada">
				<header>
					<h1><?php __e('Especies'); ?>:</h1>
				</header>
				<ul class="rota-especies">
					<?php $especies = array_chunk($rota['especies'], ceil(count($rota['especies']) / 2)); ?>
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
