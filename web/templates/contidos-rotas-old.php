<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('rotas'); ?>"><?php echo __('Rotas e espazos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo __('Todas as rotas') ?></h2>

			<nav>
				<button class="btn"><?php __e('Propón unha rota'); ?></button>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent">
			<header>
				<form action="<?php echo path(); ?>" class="subcontent-filter" method="get">
					<fieldset>
						<label>
							<?php
							echo $Form->select(array(
								'options' => array(
									'baixa' => __('Baixa'),
									'media' => __('Media'),
									'alta' => __('Alta')
								),
								'variable' => 'dificultade',
								'first_option' => __('Filtrar por dificultade')
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
								'first_option' => __('Filtrar por distancia')
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
								'first_option' => __('Filtrar por duracion')
							));
							?>
						</label>

							<label>
								<button type="submit" class="btn"><?php __e('Filtrar'); ?></button>
							</label>
					</fieldset>
				</form>
			</header>

			<?php if ($rotas) { ?>

			<div class="ly-11">
				<?php foreach (array_chunk($rotas, 2) as $fila) { ?>

				<ul class="listaxe ly-e1">
					<?php
					$Templates->render('aux-rota.php', array(
						'rota' => $fila[0]
					));
					?>
				</ul>

				<?php if (count($fila[1])) { ?>
				<ul class="listaxe ly-e2">
					<?php
					$Templates->render('aux-rota.php', array(
						'rota' => $fila[1]
					));
					?>
				</ul>
				<?php } ?>

				<?php } ?>
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
</section>
