<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Rotas e espazos'); ?></h1>

			<nav>
				<a class="btn icon-rss" href="<?php echo path('rss', 'espazos'); ?>"><?php __e('Subscríbete'); ?></a>

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
					<a href="<?php echo path('rotas'); ?>"><?php echo __('Rotas'); ?></a>
				</li>
				<li>
					<a href="<?php echo path('espazos'); ?>" class="selected"><?php echo __('Espazos'); ?></a>
				</li>
			</ul>

			<section class="subcontent" id="espazos">
				<header>
					<form class="subcontent-filter" method="get">
						<fieldset>
							<label>
								<?php
								echo ' '.$Form->select(array(
									'options' => $tipos,
									'variable' => 'tipo',
									'option_value' => 'id',
									'option_text' => 'nome',
									'class' => 'w3',
									'first_option' => '',
									'data-placeholder' => __('Filtrar por tipo')
								));
								?>
							</label>

							<label>
								<?php
								echo ' '.$Form->select(array(
									'options' => $territorios,
									'variable' => 'zona',
									'option_value' => 'url',
									'option_text' => 'nome',
									'class' => 'w3',
									'first_option' => '',
									'data-placeholder' => __('Filtrar por zona')

								));
								?>
							</label>							

							<label>
								<?php
								echo ' '.$Form->select(array(
									'options' => $figuras,
									'variable' => 'figura',
									'option_value' => 'id',
									'option_text' => 'nome',
									'class' => 'w3',
									'first_option' => '',
									'data-placeholder' => __('Filtrar por figuras de protección')

								));
								?>
							</label>
                            
                            <label>
								<select name="validado" class="w3" data-placeholder="<?php __e('Validado o no validado'); ?>">
                                    <option value=""></option>
                                    <option value="1" <?php echo $Vars->var['validado'] === '1' ? 'selected' : ''; ?>><?php __e('Validados'); ?></option>
                                    <option value="0" <?php echo $Vars->var['validado'] === '0' ? 'selected' : ''; ?>><?php __e('No validados'); ?></option>
                                </select>
							</label>
							<button type="submit" class="btn btn-highlight"><?php __e('Filtrar'); ?></button>
						</fieldset>
					</form>
				</header>

				<?php if ($espazos) { ?>

				<div class="ly-11">
					<?php $metade = ceil(count($espazos) / 2); ?>

					<ul class="listaxe ly-e1">
						<?php
						foreach ($espazos as $num => $espazo) {
							echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

							$Templates->render('aux-espazo.php', array(
								'espazo' => $espazo
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
					<?php __e('Non se atoparon espazos con eses filtros.'); ?>
				</div>

				<?php } ?>
			</section>
		</div>
	</div>
</section>
