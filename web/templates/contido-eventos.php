<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Novas e eventos'); ?></h1>

			<nav>
				<a class="btn icon-rss" href="<?php echo path('rss', 'eventos'); ?>"><?php __e('Subscríbete'); ?></a>
				
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

	<div class="content wrapper">
		<section class="subcontent calendario">
			<?php echo calendario($ym, $calendario); ?>
		</section>
	</div>
</section>
