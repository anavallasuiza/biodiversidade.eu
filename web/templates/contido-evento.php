<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Novas e eventos'); ?></h1>

			<?php if ($user && ($Acl->check('action', 'evento-crear') || $Acl->check('action', 'evento-editar'))) { ?>
			<nav>
                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                    	<?php if ($Acl->check('action', 'evento-crear')) { ?>
                        <li>
							<a href="<?php echo path('editar', 'evento'); ?>">
								<i class="icon-plus"></i>
								<?php __e('Crear evento'); ?>
							</a>
						</li>
						<?php } ?>

						<?php if ($Acl->check('action', 'evento-editar')) { ?>
						<li>
							<a href="<?php echo path('editar', 'evento', $evento['url']); ?>">
								<i class="icon-pencil"></i>
								<?php __e('Editar evento'); ?>
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

			<article class="actividade actividade-permalink">
				<header>
					<time>
						<span class="dia"><?php echo date('d', strtotime($evento['data'])); ?></span>
						<span class="mes"><?php echo $Datetime->getMonth(date('m', strtotime($evento['data'])), true); ?></span>
					</time>

					<h1><?php echo $evento['titulo']; ?></h1>

					<ul class="info-actividade">
						<li><?php echo $evento['lugar']; ?></li>
						<li><?php __e('Hora: %s', date('H:i', strtotime($evento['data']))); ?></li>
					</ul>
				</header>

				<?php
				if ($adxuntos) {
					$Templates->render('aux-adxuntos.php', array(
						'adxuntos' => $adxuntos
					));
				}
				?>

				<div class="actividade-intro">
					<?php echo $evento['texto']; ?>
				</div>

                <?php 
				if ($imaxes) {
					$Templates->render('aux-gallery.php', array(
						'images' => $imaxes,
						'rel' => 'galeria-espazo'
					));
				}
				?>
			</article>

            <?php
            $Templates->render('aux-comentarios.php', array(
                'comentarios' => $comentarios
            ));
            ?>
		</section>

		<section class="subcontent ly-e2">
            <?php $Templates->render('aux-traducir.php', array(
                'idioma' => $evento['idioma']
            )); ?>

			<?php if ($eventos) { ?>
			<header>
				<h1><?php __e('Próximos eventos'); ?></h1>
			</header>

			<ul class="listaxe">
				<?php
				foreach ($eventos as $evento) {
					$Templates->render('aux-evento.php', array(
						'evento' => $evento
					));
				}
				?>
			</ul>

			<a class="btn-link" href="<?php echo path('eventos'); ?>">
				<i class="icon-arrow-right"></i>
				<?php echo __('Ver todos os eventos') ?>
			</a>
			<?php } ?>
		</section>
	</div>
</section>
