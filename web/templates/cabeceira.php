<?php defined('ANS') or die(); ?>

<div class="top-header">
	<nav class="main">
		<div class="wrapper ly-menu">
			<ul class="main-idiomas ly-menu-right">
				<li class="<?php echo $Vars->getLanguage('gl') ? 'selected': ''; ?>">
					<a href="<?php echo get('lang', 'gl'); ?>"><strong><?php echo __('Gal'); ?><small>-rag</small></strong></a>
				</li>
				<li class="<?php echo $Vars->getLanguage('pt') ? 'selected': ''; ?>">
					<a href="<?php echo get('lang', 'pt'); ?>"><strong><?php echo __('Port'); ?><small>-ao</small></strong></a>
				</li>
				<li class="<?php echo $Vars->getLanguage('es') ? 'selected': ''; ?>">
					<a href="<?php echo get('lang', 'es'); ?>"><?php echo __('Cast'); ?></a>
				</li>
				<li class="<?php echo $Vars->getLanguage('en') ? 'selected': ''; ?>">
					<a href="<?php echo get('lang', 'en'); ?>"><?php echo __('Eng'); ?></a>
				</li>
			</ul>			

			<form action="<?php echo path('buscar'); ?>" class="ly-buscador hidden" method="get">
				<fieldset>
					<input name="q" class="no-appearance" placeholder="<?php echo __('Buscar') ?>" type="search" required/>
					<input type="submit" value="" />
				</fieldset>
			</form>

			<ul class="main-menu ly-menu-left">
				<li <?php echo in_array(SECTION, array('catalogo', 'especie')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('catalogo', 'mapa'); ?>"><?php echo __('Catálogo'); ?></a></li>
				<li <?php echo in_array(SECTION, array('avistamentos', 'avistamento')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('avistamentos'); ?>"><?php echo __('Observacións'); ?></a></li>
				<li <?php echo in_array(SECTION, array('ameazas', 'ameaza', 'iniciativas', 'iniciativa')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('ameazas'); ?>"><?php echo __('Ameazas'); ?></a></li>
				<li <?php echo in_array(SECTION, array('rotas', 'rota', 'espazos', 'espazo')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('rotas'); ?>"><?php echo __('Rotas e espazos'); ?></a></li>
				<li <?php echo in_array(SECTION, array('novas', 'nova', 'eventos', 'evento')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('novas'); ?>"><?php echo __('Novas e eventos'); ?></a></li>
				<li <?php echo in_array(SECTION, array('blogs', 'blog', 'post', 'proxectos', 'proxecto')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('blogs'); ?>"><?php echo __('Blogs e proxectos'); ?></a></li>
				<li <?php echo (SECTION === 'documentacion') ? 'class="selected"' : ''; ?>><a href="<?php echo path('documentacion'); ?>"><?php echo __('Documentación didáctica'); ?></a></li>
				<li <?php echo ((SECTION === 'info') && $Vars->getPath(1, 'proxecto')) ? 'class="selected"' : ''; ?>><a href="<?php echo path('info', 'proxecto'); ?>"><?php echo __('O proxecto'); ?></a></li>
			</ul>
		</div>
	</nav>

	<header class="main">
		<div class="wrapper clear">
			<h1 class="image logo-biodiv"><a href="<?php echo path(''); ?>"><?php echo __('Biodiversidade ameazada'); ?></a></h1>

			<div class="login <?php echo $user ? 'logged' : '' ; ?>">
				<?php if ($user) { ?>

				<a href="<?php echo path('perfil'); ?>" class="avatar"><?php
					echo $Html->img(array(
						'src' => $user['avatar'],
						'alt' => $user['nome']['title'],
						'width' => 50,
						'height' => 50,
						'transform' => 'zoomCrop,50,50',
						'class' => 'usuario'
					));
					?></a>
				<h2><?php __e('Ola %s!', '<a href="'.path('perfil').'">'.$user['nome']['title'].'</a>'); ?></h2>

				<ul>
					<li><?php echo $Html->a(__('Saír'), '?phpcan_action=sair'); ?></li>
				</ul>

				<?php } else { ?>

				<a class="btn icon-signin modal-ajax" href="<?php echo path('entrar'); ?>">
					<h2><?php __e('Área de traballo'); ?></h2>
					<p><?php __e('Entra para subir novas especies, comentar, etc') ;?></p>
				</a>
				<?php } ?>
			</div>
		</div>
	</header>
</div>

<?php include ($Templates->file('aux-alerta.php')); ?>
