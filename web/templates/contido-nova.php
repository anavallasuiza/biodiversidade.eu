<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Novas e eventos'); ?></h1>

			<?php if ($user && ($Acl->check('action', 'nova-crear') || $Acl->check('action', 'nova-editar'))) { ?>
			<nav>
                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                    	<?php if ($Acl->check('action', 'crear-editar')) { ?>
                        <li>
							<a href="<?php echo path('editar', 'nova'); ?>">
								<i class="icon-plus"></i>
								<?php __e('Crear nova'); ?>
							</a>
						</li>
						<?php } ?>

						<?php if ($Acl->check('action', 'nova-editar')) { ?>
						<li>
							<a href="<?php echo path('editar', 'nova', $nova['url']); ?>">
								<i class="icon-pencil"></i>
								<?php __e('Editar nova'); ?>
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

			<article class="nova nova-permalink">
				<header>
					<h1><?php echo $nova['titulo']; ?></h1>
				</header>

				<footer>
					<?php 
					echo ucfirst($Html->time($nova['data'], '', 'absolute'));

					if ($nova['comentarios']) {
						echo (count($nova['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($nova['comentarios']));
					}
					?>
				</footer>

				<div class="nova-intro">
					<?php echo $nova['texto']; ?>
				</div>

				<?php
				if ($imaxes) {
					$Templates->render('aux-gallery.php', array(
						'images' => $imaxes,
						'hide' => 'templates|img/logo-imaxe.png'
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
                'idioma' => $nova['idioma']
            )); ?>

			<?php if ($outros_comentarios) { ?>
			<header>
				<h1><?php __e('Últimos comentarios'); ?></h1>
			</header>

				<ul class="listaxe">
					<?php
					foreach ($outros_comentarios as $comentario) {
						$Templates->render('aux-comentario-ultimos.php', array(
							'comentario' => $comentario
						));
					}
					?>
				</ul>
			<?php } ?>
		</section>
	</div>
</section>
