<div data-role="page" id="page-buscar-especies">
	<div data-role="header" data-add-back-btn="true">
		<a href="<?php echo path(''); ?>" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<div data-role="content">
		<div class="content-header">
			<h1><?php echo __('Especies atopadas') ?></h1>
		</div>

        <?php if (!$especies) { ?>
        <p><?php __e('Non se atoparon avistamentos nas proximidades'); ?></p>
        <?php } else { ?>
		<ul data-role="listview" data-inset="true" data-filter="true">
			<?php foreach ($especies as $especie) { ?>
			<li><a href="<?php echo path('detalle', 'especie', $especie['url']); ?>" data-transition="slide">
				<img src="<?php echo $Html->imgSrc($especie['imaxe'], 'zoomCrop,50,50'); ?>" width="80" height="80">
				<h2><?php echo $especie['nome']; ?></h2>
				<p>Distancia: <?php echo str_replace('.', ',', $especie['distancia']); ?> Km</p>
			</a></li>
			<?php } ?>
		</ul>
        <?php } ?>
	</div>
</div>
