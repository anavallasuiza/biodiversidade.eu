<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Rotas e espazos'); ?></h1>

			<nav>
				<a class="btn icon-rss" href="<?php echo path('rss', 'rotas'); ?>"><?php __e('Subscríbete'); ?></a>

				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<?php if ($user) { ?>

						<li>
							<a href="<?php echo path('editar', 'rota'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova rota'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo path('editar', 'espazo'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo espazo'); ?>
							</a>
						</li>

						<?php } else { ?>

						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova rota'); ?>
							</a>
						</li>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo espazo'); ?>
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
					<a href="<?php echo path(); ?>" class="selected"><?php echo __('Rotas'); ?></a>
				</li>
				<li>
					<a href="<?php echo path('espazos'); ?>"><?php echo __('Espazos'); ?></a>
				</li>
			</ul>

			<section class="subcontent" id="rotas">
				<header>
					<form action="<?php echo path(); ?>" class="subcontent-filter" method="get">
						<fieldset>
							<label>
								<?php
								echo $Form->select(array(
									'options' => array(
										'baixa' => __('Baixa'),
										'media' => __('Media'),
										'alta' => __('Alta'),
									),
									'variable' => 'dificultade',
									'class' => 'w3',
									'first_option' => '',
									'data-placeholder' => __('Filtrar por dificultade')

								));
								?>
							</label>

							<label>
								<?php
								echo $Form->select(array(
									'options' => array(
										'<10' => __('< 10 km'),
										'10-20' => __('10 - 20 km'),
										'>20' => __('> 20 km')
									),
									'variable' => 'distancia',
									'class' => 'w3',
									'first_option' => '',
									'data-placeholder' => __('Filtrar por distancia')

								));
								?>
							</label>

							<label>
								<?php
								echo $Form->select(array(
									'options' => array(
										'<2' => __('< 2 horas'),
										'2-4' => __('2 - 4 horas'),
										'>4' => __('> 4 horas')
									),
									'variable' => 'duracion',
									'class' => 'w3',
									'first_option' => '',
									'data-placeholder' => __('Filtrar por duracion')


								));
								?>
							</label>

							<button type="submit" class="btn btn-highlight"><?php __e('Filtrar'); ?></button>
						</fieldset>
					</form>
				</header>

				<?php if ($rotas) { ?>

				<div class="ly-11">
					<?php $metade = ceil(count($rotas) / 2); ?>

					<ul class="listaxe ly-e1">
						<?php
						foreach ($rotas as $num => $rota) {
							echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

							$Templates->render('aux-rota.php', array(
								'rota' => $rota
							));
						}
						?>
					</ul>
				</div>

				<?php
				$Templates ->render('aux-paxinacion.php', array(
					'pagination' => $Data->pagination
				));
				?>

				<?php } else { ?>

				<div class="alert alert-info">
					<?php __e('Non se atoparon rotas con eses filtros.'); ?>
				</div>

				<?php } ?>
			</section>
		</div>
	</div>
</section>
