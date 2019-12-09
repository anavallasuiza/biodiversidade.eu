<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Documentación didáctica'); ?></h1>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
			<p><?php __e('texto-intro-documentaion-didactica'); ?></p>

			<div class="tabs">
				<ul>
					<?php foreach ($grupos as $grupo) { ?>
					<li><a href="#clasificacion-<?php echo $grupo['id']; ?>"><?php echo $grupo['titulo']; ?></a></li>
					<?php } ?>
				</ul>

				<?php foreach ($grupos as $grupo) { ?>
				<section id="clasificacion-<?php echo $grupo['id']; ?>">
					<ul class="lista-doc">
						<?php foreach ($grupo['documentacion'] as $documento) { ?>
						<li>
							<article class="doc">
								<header>
									<h1><a href="<?php echo fileWeb('uploads|'.$documento['arquivo']); ?>"><?php echo $documento['titulo']; ?></a></h1>
								</header>

								<div class="intro"><?php echo $documento['texto']; ?></div>

								<a class="btn" href="<?php echo fileWeb('uploads|'.$documento['arquivo']); ?>">
									<i class="icon-download"></i>
									<?php echo __('Descargar'); ?>
								</a>

								<small><?php __e('Engadido o %s', strtolower($Html->time($documento['data'], '', 'absolute'))); ?></small>
							</article>
						</li>
						<?php } ?>
					</ul>
				</section>
				<?php } ?>
			</div>
		</section>		

		<section class="subcontent ly-e2 sidebar-ficha">
			<div class="info">
				<header>
					<h1><?php __e('Actividades didácticas'); ?></h1>
				</header>

				<div class="intro">
					<p><?php __e('texto-intro-actividades-didacticas'); ?></p>
				</div>

				<a class="btn" href="<?php echo path('actividades-didacticas'); ?>">
					<i class="icon-plus"></i>
					<?php echo __('Saber máis'); ?>
				</a>
			</div>
		</section>
	</div>
</section>
