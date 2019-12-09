<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Novas e eventos'); ?></h1>

			<nav>
				<a class="btn icon-rss" href="<?php echo path('rss', 'novas'); ?>"><?php __e('Subscríbete'); ?></a>
				
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<?php if ($user) { ?>

						<?php if ($Acl->check('action', 'nova-crear')) { ?>
						<li>
							<a href="<?php echo path('editar', 'nova'); ?>">
								<i class="icon-plus"></i> <?php __e('Crear nova'); ?>
							</a>
						</li>
						<?php } ?>
						<li>
							<a href="<?php echo path('editar', 'evento'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo evento'); ?>
							</a>
						</li>

						<?php } else { ?>

						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Crear nova'); ?>
							</a>
						</li>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo evento'); ?>
							</a>
						</li>

						<?php } ?>
					</ul>
				</div>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
			<ul class="listaxe">
				<?php
				foreach ($novas as $nova) {
					$Templates->render('aux-nova.php', array(
						'nova' => $nova
					));
				}
				?>
			</ul>

			<?php
			$Templates->render('aux-paxinacion.php', array(
				'pagination' => $Data->pagination
			));
			?>
		</section>

		<?php if ($eventos) { ?>
		<section class="subcontent ly-e2">
			<header>
				<h1><?php __e('Próximos eventos'); ?></h1>
			</header>

			<div>
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
			</div>
		</section>
		<?php } ?>
	</div>
</section>
