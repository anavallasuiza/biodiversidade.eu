<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Actividades didacticas'); ?></h1>

			<?php if ($user) { ?>
			<nav>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a href="<?php echo path('editar', 'didactica'); ?>">
								<i class="icon-plus"></i>
								<?php __e('Crear unha nova actividade didactica'); ?>
							</a>
						</li>

						<?php if ($Acl->check('action', 'didactica-editar')) { ?>
						<li>
							<a href="<?php echo path('editar', 'didactica', $didactica['url']); ?>">
								<i class="icon-pencil"></i>
								<?php __e('Editar'); ?>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</nav>
			<?php } ?>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
            <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
                <i class="icon-arrow-left"></i>
                <?php echo __('Voltar'); ?>
            </a>

			<article class="act-didactica act-didactica-permalink">
				<header>
					<h1><?php echo $didactica['titulo']; ?></h1>				
				</header>

				<?php
				if ($adxuntos) {
					$Templates->render('aux-adxuntos.php', array(
						'adxuntos' => $adxuntos
					));
				}
				?>

				<div class="intro">
					<?php echo $didactica['intro']; ?>
				</div>

				<?php if ($didactica['xustificacion']) { ?>
				<div class="text">
					<h3><?php __e('Xustificación'); ?></h3>
					<?php echo $didactica['xustificacion']; ?>
				</div>
				<?php } ?>

				<?php if ($didactica['desenvolvemento']) { ?>
				<div class="text">
					<h3><?php __e('Desenvolvemento'); ?></h3>
					<?php echo $didactica['desenvolvemento']; ?>
				</div>
				<?php } ?>

				<?php if ($didactica['obxectivos']) { ?>
				<div class="text">
					<h3><?php __e('Obxectivos'); ?></h3>
					<?php echo $didactica['obxectivos']; ?>
				</div>
				<?php } ?>

				<?php if ($didactica['competencias']) { ?>
				<div class="text">
					<h3><?php __e('Competencias'); ?></h3>
					<?php echo $didactica['competencias']; ?>
				</div>
				<?php } ?>

				<?php if ($didactica['duracion']) { ?>
				<div class="text">
					<h3><?php __e('Duración'); ?></h3>
					<?php echo $didactica['duracion']; ?>
				</div>
				<?php } ?>

				<?php if ($didactica['material']) { ?>
				<div class="text">
					<h3><?php __e('Material'); ?></h3>
					<?php echo $didactica['material']; ?>
				</div>
				<?php } ?>

				<?php if ($didactica['recursos']) { ?>
				<div class="text">
					<h3><?php __e('Recursos'); ?></h3>
					<?php echo $didactica['recursos']; ?>
				</div>
				<?php } ?>
			</article>
		</section>

		<section class="ly-e2 sidebar-didactica subcontent">
            <?php $Templates->render('aux-traducir.php', array(
                'idioma' => $didactica['idioma']
            )); ?>

			<?php if ($didacticas) { ?>
			<header>
				<h1><?php echo __('Outras actividades'); ?></h1>
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
			<?php } ?>
		</section>
	</div>
</section>
