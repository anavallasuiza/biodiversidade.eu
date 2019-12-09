<div data-role="page" id="page-buscar-especies">
	<div data-role="header" data-add-back-btn="true">
		<a href="#page-home" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<div data-role="content" class="especie">
		<div class="content-header">
			<h1><?php echo $rota['titulo']; ?></h1>
		</div>

		<div>
			<h2><?php __e('Datos do terreo'); ?></h2>

			<ul class="rota-informacion">
                <?php if ($rota['territorios']) { ?>
				<li>
                    <p><strong><?php echo $rota['concellos']['nome']['title'].' '.$rota['provincias']['nome']['title'] . ' ' . $rota['territorios']['nome']; ?></strong></p>
				</li>
				<?php } ?>
                
				<?php if ($rota['lugar']) { ?>
				<li>
                    <p><strong><?php echo $rota['lugar']; ?></strong></p>
				</li>
				<?php } ?>
                
                <?php if ($rota['dificultade']) { ?>
				<li><?php __e('Dificultade'); ?>: <strong><?php echo ucfirst($rota['dificultade']); ?></strong></li>
                <?php } ?>
                
				<li><?php __e('Distancia'); ?>: <strong><?php echo (intval($rota['distancia'] > 1000) ? number_format($rota['distancia'] / 1000, 1, ',', '') . ' Km': $rota['distancia'] . ' m'); ?></strong></li>
                
                <?php if ($rota['duracion']) { ?>
				<li><?php __e('Duración'); ?>: <strong><?php echo gmdate('H \h i \m', ($rota['duracion'] * 60)); ?></strong></li>
                <?php } ?>
			</ul>
		</div>

		<div class="especie-seccion">
			<?php echo $rota['texto']; ?>
		</div>

		<?php if (intval($rota['votos_media'])) { ?>
		<div class="rota-votar">
			<h2><?php __e('Valoración media'); ?></h2>
			<p class="puntos"><?php echo str_replace('.', ',', round($rota['votos_media'], 1)); ?></p>
		</div>
		<?php } ?>

		<a data-role="button" data-icon="arrow-r" data-iconpos="right" href="<?php echo path(array('scene' => 'web'), 'rota', $rota['url']); ?>"><?php __e('Ver a ficha completa'); ?></a>
	</div>
</div>
