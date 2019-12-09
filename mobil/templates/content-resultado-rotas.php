<div data-role="page" id="page-buscar-especies">
	<div data-role="header" data-add-back-btn="true">
		<a href="<?php echo path(''); ?>" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<div data-role="content">
		<div class="content-header">
			<h1><?php echo __('Rotas atopadas') ?></h1>
		</div>

        <?php if (!$rotas) { ?>
        <p><?php __e('Non se atoparon rotas nas proximidades'); ?></p>
        <?php } else { ?>
		<ul data-role="listview" data-inset="true" data-filter="true">
			<?php foreach ($rotas as $rota) { ?>
			<li><a href="<?php echo path('detalle', 'rota', $rota['url']); ?>" data-transition="slide">
				<img src="<?php echo $Html->imgSrc($rota['imaxe']); ?>" width="80" height="80">
				<h2><?php echo $rota['nome']; ?></h2>
				<p><?php echo __('Distancia'); ?>: <?php echo str_replace('.', ',', $rota['distancia']); ?> Km</p>
			</a></li>
			<?php } ?>
		</ul>
        <?php } ?>
	</div>
</div>
