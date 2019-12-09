<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Didáctica'); ?></h1>

			<nav>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<?php if ($user) { ?>
							<a href="<?php echo path('editar', 'didactica'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova actividade didáctica'); ?>
							</a>
							<?php } else { ?>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova actividade didáctica'); ?>
							</a>
							<?php } ?>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-1f">
		<section class="ly-e1">
			<nav class="menu-lateral-didactica">
				<ul>	
					<li><a href="#presentacion"><i class="icon-chevron-right"></i> <?php echo __('Presentación'); ?></a></li>
					<li><a href="#obxectivos"><i class="icon-chevron-right"></i> <?php echo __('Obxectivos e competencias'); ?></a></li>
					<li><a href="#fichas"><i class="icon-chevron-right"></i> <?php echo __('Fichas'); ?></a></li>
					<li><a href="#actividades"><i class="icon-chevron-right"></i> <?php echo __('Actividades'); ?></a></li>
				</ul>
			</nav>
		</section>		

		<section class="subcontent ly-e2">
			<?php echo $texto['texto']; ?>

			<div class="bloque-didactica" id="actividades">
				<header>
					<h1><?php echo __('Actividades'); ?></h1>
				</header>

				<ul class="lista-actividades">
					<?php foreach ($didacticas as $didactica) { ?>
					<li>
						<article class="act-didactica">
							<header>
								<h1><a href="<?php echo path('didactica', $didactica['url']); ?>"><?php echo $didactica['titulo']; ?></a></h1>
							</header>

							<div class="intro"><?php echo $didactica['intro']; ?></div>
						</article>
					</li>
					<?php } ?>
				</ul>
			</div>
		</section>
	</div>
</section>
