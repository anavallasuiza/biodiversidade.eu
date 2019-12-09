<div data-role="page" id="page-notas">
	<div data-role="header" data-add-back-btn="true">
		<a href="<?php echo path('') ?>" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<div data-role="content">
		<div class="content-header">
			<h1><?php echo __('Notas de campo'); ?></h1>
		</div>

		<a href="#page-observacion" data-transition="slide" data-role="button" data-theme="a" data-icon="plus"><?php echo __('Nova observación') ?></a>
		<a href="#page-ameaza" data-transition="slide" data-role="button" data-theme="a" data-icon="plus"><?php echo __('Nova ameaza') ?></a>
		<a href="#page-rota" data-transition="slide" data-role="button" data-theme="a" data-icon="plus"><?php echo __('Nova rota') ?></a>

		<div id="notas-lista">
			<h2><?php __e('Notas xa creadas'); ?></h2>

			<ul data-role="listview" data-inset="true"></ul>
		</div>
	</div>

	<div data-role="footer" class="ui-bar" data-theme="b">
		<button class="sincronizar" data-href="<?php echo path(''); ?>" type="button" data-role="button" data-inline="true" data-icon="refresh">
            <?php echo __('Sincronizar notas co meu usuario na web') ?>
        </button>
	</div>
</div>

<div data-role="page" id="page-observacion">
	<div data-role="header" data-add-back-btn="true">
		<a href="#page-notas" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Cancelar') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<form data-role="content" class="edicion-nota" action="">
		<div class="content-header">
			<h1><?php echo __('Notas &gt; Observación') ?></h1>
		</div>

		<label for="observacion-titulo"><?php echo __('Titulo da observación') ?></label>
		<input type="text" id="observacion-titulo" name="title" required>
		<textarea id="observacion-texto" name="text"></textarea>

		<h2><?php __e('Xeolocalización'); ?></h2>

		<?php /*<ul data-role="listview" data-inset="true" id="observacion-puntos"></ul>*/ ?>

        <ul data-role="listview" data-inset="true" id="observacion-puntos" data-filter="true"></ul>

        <a href="#popup-punto-observacion" id="observacion-novo-punto" data-rel="popup" data-role="button" data-icon="plus" data-inline="true" data-position-to="window"><?php echo __('Novo punto'); ?></a>

		<div data-role="footer" class="ui-bar" data-theme="b">
			<button class="remove" type="button" data-role="button" data-inline="true" data-icon="delete"><?php echo __('Borrar') ?></button>
			<button type="submit" data-role="button" data-theme="a" data-inline="true" data-icon="check"><?php echo __('Gardar') ?></button>
		</div>
	</form>

	<div data-role="popup" id="popup-punto-observacion" data-dismissible="false">
		<div data-role="header" class="ui-corner-top" data-theme="b">
			<h1><?php echo __('Editar punto'); ?></h1>
		</div>

		<a href="#" data-rel="back" data-role="button" data-theme="b" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		
		<div data-role="content">
			<form class="edicion-punto" action="">
				<label for="punto-observacion-texto"><?php echo __('Anotacións do punto') ?></label>
				<textarea id="punto-observacion-texto" name="text"></textarea>

				<span data-role="button" data-theme="a" class="btn-xeolocalizacion"><?php echo __('Xeolocalizar usando o móbil'); ?></span>
				<input type="hidden" name="lonlat-coords">
				<p class="lonlat-coords"></p>

				<label for="punto-observacion-mgrs"><?php echo __('Ou meter manualmente MGRS en datum WGS84 (ETRS89):') ?></label>

				<input type="text" name="mgrs-coords" id="punto-observacion-mgrs" pattern="^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$" placeholder="p.e.: 29TNH082101 - ^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$" />

				<button class="remove" type="button" data-role="button" data-inline="true" data-icon="delete" data-confirm="<?php __e('Esta seguro de que desexa eliminar este punto?'); ?>"><?php echo __('Borrar') ?></button>
				<button type="submit" data-role="button" data-theme="a" data-inline="true" data-icon="check"><?php echo __('Gardar') ?></button>
			</form>
		</div>
	</div>
</div>

<div data-role="page" id="page-ameaza">
	<div data-role="header" data-add-back-btn="true">
		<a href="#page-notas" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Cancelar') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<form data-role="content" class="edicion-nota" action="">
		<div class="content-header">
			<h1><?php echo __('Notas &gt; Ameaza') ?></h1>
		</div>

		<label for="ameaza-titulo"><?php echo __('Titulo da ameaza') ?></label>
		<input type="text" id="ameaza-titulo" name="title" required>

		<textarea id="ameaza-texto" name="text"></textarea>

		<h2><?php __e('Xeolocalización'); ?></h2>

		<ul data-role="listview" data-inset="true" id="ameaza-puntos" data-filter="true"></ul>

		<a href="#popup-punto-ameaza" id="ameaza-novo-punto" data-rel="popup" data-role="button" data-icon="plus" data-inline="true" data-position-to="window"><?php echo __('Novo punto'); ?></a>

		<div data-role="footer" class="ui-bar" data-theme="b">
			<button class="remove" type="button" data-role="button" data-inline="true" data-icon="delete"><?php echo __('Borrar') ?></button>
			<button type="submit" data-role="button" data-theme="a" data-inline="true" data-icon="check"><?php echo __('Gardar') ?></button>
		</div>
	</form>

	<div data-role="popup" id="popup-punto-ameaza">
		<div data-role="header" class="ui-corner-top" data-theme="b">
			<h1><?php echo __('Editar punto'); ?></h1>
		</div>

		<a href="#" data-rel="back" data-role="button" data-theme="b" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		
		<div data-role="content">
			<form class="edicion-punto" action="">
				<label for="punto-ameaza-texto"><?php echo __('Anotacións do punto') ?></label>
				<textarea id="punto-ameaza-texto" name="text"></textarea>

				<span data-role="button" data-theme="a" class="btn-xeolocalizacion"><?php echo __('Xeolocalizar usando o móbil'); ?></span>
				<input type="hidden" name="lonlat-coords">
				<p class="lonlat-coords"></p>

				<label for="punto-observacion-mgrs"><?php echo __('Ou meter manualmente MGRS en datum WGS84 (ETRS89):') ?></label>

				<input type="text" name="mgrs-coords" id="punto-observacion-mgrs" pattern="^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$" placeholder="p.e.: 29TNH082101 - ^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$" />

				<button class="remove" type="button" data-role="button" data-inline="true" data-icon="delete"><?php echo __('Borrar') ?></button>
				<button type="submit" data-role="button" data-theme="a" data-inline="true" data-icon="check"><?php echo __('Gardar') ?></button>
			</form>
		</div>
	</div>
</div>

<div data-role="page" id="page-rota">
	<div data-role="header" data-add-back-btn="true">
		<a href="#page-notas" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Cancelar') ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<form data-role="content" class="edicion-nota" action="">
		<div class="content-header">
			<h1><?php echo __('Notas &gt; Rota') ?></h1>
		</div>

		<label for="rota-titulo"><?php echo __('Titulo da rota') ?></label>
		<input type="text" id="rota-titulo" name="title" required>
		<textarea id="rota-texto" name="text"></textarea>

		<h2><?php __e('Xeolocalización'); ?></h2>

		<ul data-role="listview" data-inset="true" id="rota-puntos" data-filter="true"></ul>

		<a href="#popup-punto-rota" id="rota-novo-punto" data-rel="popup" data-role="button" data-icon="plus" data-inline="true" data-position-to="window"><?php echo __('Novo punto'); ?></a>

		<div data-role="footer" class="ui-bar" data-theme="b">
			<button class="remove" type="button" data-role="button" data-inline="true" data-icon="delete"><?php echo __('Borrar') ?></button>
			<button type="submit" data-role="button" data-theme="a" data-inline="true" data-icon="check"><?php echo __('Gardar') ?></button>
		</div>
	</form>

	<div data-role="popup" id="popup-punto-rota">
		<div data-role="header" class="ui-corner-top" data-theme="b">
			<h1><?php echo __('Editar punto'); ?></h1>
		</div>

		<a href="#" data-rel="back" data-role="button" data-theme="b" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		
		<div data-role="content">
			<form class="edicion-punto" action="">
				<label for="punto-rota-texto"><?php echo __('Anotacións do punto') ?></label>
				<textarea id="punto-rota-texto" name="text"></textarea>

				<span data-role="button" data-theme="a" class="btn-xeolocalizacion"><?php echo __('Xeolocalizar usando o móbil'); ?></span>
				<input type="hidden" name="lonlat-coords">
				<p class="lonlat-coords"></p>

				<button class="remove" type="button" data-role="button" data-inline="true" data-icon="delete"><?php echo __('Borrar') ?></button>
				<button type="submit" data-role="button" data-theme="a" data-inline="true" data-icon="check"><?php echo __('Gardar') ?></button>
			</form>
		</div>
	</div>
</div>
