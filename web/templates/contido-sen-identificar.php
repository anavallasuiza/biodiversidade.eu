<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Catálogo'); ?></h1>

			<nav>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<?php if ($user) { ?>

						<li>
							<a href="<?php echo path('editar', 'avistamento'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo avistamento'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo path('editar', 'especie'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova especie'); ?>
							</a>
						</li>

						<?php } else { ?>

						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo avistamento'); ?>
							</a>
						</li>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova especie'); ?>
							</a>
						</li>

						<?php } ?>
					</ul>
				</div>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
		<div class="tabs tabs-page">
			<ul>
				<li>
					<a href="<?php echo path('catalogo', 'mapa'); ?>"><?php echo __('Mapa catálogo'); ?></a>
				</li>
				<li>
					<a href="<?php echo path('catalogo'); ?>"><?php echo __('Especies pestana'); ?></a>
				</li>
				<li class="pestana-right">
					<a href="<?php echo path(); ?>" class="selected"><i class="icon-pencil"></i> <?php echo __('Sen identificar'); ?></a>
				</li>
			</ul>

			<?php if ($avistamentos) { ?>
			<section id="avistamentos" class="listaxe">
				<div class="ly-11">
					<?php $metade = ceil(count($avistamentos) / 2); ?>

					<ul class="listaxe ly-e1">
						<?php
						foreach ($avistamentos as $num => $avistamento) {
							echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

							$Templates->render('aux-avistamento.php', array(
								'avistamento' => $avistamento
							));
						}
						?>
					</ul>

					<?php
					$Templates->render('aux-paxinacion.php', array(
						'pagination' => $pagination['avistamentos'],
						'p' => 'p-avistamentos',
						'anchor' => 'avistamentos'
					));
					?>
				</div>
			</section>
			<?php } else { ?> 
			<p class="texto-sen-resultado">
				<?php __e('Actualmente non hai especies sen identificar.'); ?>
			</p>
			<?php } ?>
		</div>
	</div>
</section>
