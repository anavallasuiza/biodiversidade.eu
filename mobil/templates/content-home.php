<div data-role="page" id="page-home">
	<div data-role="header">
		<h1><?php echo __('Portada') ?></h1>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de'); ?></a>
	</div>

	<div data-role="content">
		<div class="content-header">
			<?php echo $Html->img('templates|img/logo-biodiv.png'); ?>

			<?php if ($user) { ?>
			<h1><?php echo __('Ola, %s', $user['nome']['title']); ?></h1>
			<?php } ?>
		</div>

		<a href="#page-notas" data-transition="slide" data-role="button" data-theme="a" data-icon="edit"><?php echo __('Notas de campo') ?></a>
        <a href="#page-notificar" data-transition="slide" data-role="button" data-theme="a" data-icon="alert"><?php echo __('Notificar ás autoridades') ?></a>

		<h2><?php __e('Só con conexión a internet'); ?></h2>

		<div id="buscar-proximos">
			<button data-href="<?php echo path('resultado', 'especies'); ?>" data-transition="slide" data-role="button" data-theme="a" data-icon="search"><?php echo __('Buscar especies próximas') ?></button>
			<button data-href="<?php echo path('resultado', 'rotas'); ?>" data-transition="slide" data-role="button" data-theme="a" data-icon="search"><?php echo __('Buscar rotas próximas') ?></button>
		</div>
	</div>
</div>

<?php include($Templates->file('content-notas.php')); ?>
<?php include($Templates->file('content-notificar-autoridades.php')); ?>
