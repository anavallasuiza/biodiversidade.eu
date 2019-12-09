<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Avistamentos'); ?></h1>

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
		<section class="subcontent">
            
			<header>
				<form action="<?php echo path(); ?>" class="subcontent-filter" method="get">
					<fieldset>
						<label>
							<?php
							echo $Form->select(array(
								'options' => $grupos,
 								'variable' => 'grupo',
								'option_value' => 'id',
								'option_text' => 'nome',
								'class' => 'w2',
								'first_option' => '',
								'data-placeholder' => __('Grupo')
							));
							?>
						</label>

						<label>
							<?php
							echo ' '.$Form->select(array(
								'options' => $habitats,
 								'variable' => 'habitat',
								'option_value' => 'id',
								'option_text' => 'nome',
								'class' => 'w2',
								'first_option' => '',
								'data-placeholder' => __('Tipo de hábitat')
							));
							?>
						</label>

						<label>
							<?php
							echo ' '.$Form->select(array(
								'options' => $enum['tipo_referencia'],
								'variable' => 'fonte',
								'option_text_as_value' => true,
								'gettext' => true,
								'class' => 'w3',
								'first_option' => '',
								'data-placeholder' => __('Tipo de fonte de datos')
							));
							?>
						</label>

						<label>
							<?php
							echo ' '.$Form->select(array(
								'options' => $anos,
								'variable' => 'ano',
								'option_value' => 'ano',
								'option_text' => 'ano',
								'class' => 'w1',
								'first_option' => '',
								'data-placeholder' => __('Ano')
							));
							?>
						</label>

						<label>
							<?php
							echo ' '.$Form->select(array(
								'options' => $observadores,
								'variable' => 'observador',
								'option_title' => 'nome',
								'option_value' => 'id',
								'class' => 'w3',
								'first_option' => '',
								'data-placeholder' => __('Observador')
							));
							?>
						</label>

						<label>
							<?php
							echo ' '.$Form->select(array(
								'options' => $ameazas,
								'variable' => 'ameaza',
								'option_value' => 'id',
								'option_text' => 'nome',
								'class' => 'w3',
								'first_option' => '',
								'data-placeholder' => __('Ameaza principal')
							));
							?>
						</label>
                        
                        <label>
							<?php
							echo ' '.$Form->select(array(
								'options' => $proteccions_tipos,
								'variable' => 'proteccion',
								'option_value' => 'id',
								'option_text' => 'nome',
								'class' => 'w3',
								'first_option' => '',
								'data-placeholder' => __('Tipo de protección')
							));
							?>
						</label>

						<label>
							<?php
							echo ' '.$Form->checkbox(array(
								'variable' => 'validada',
								'label_text' => __('Só validados'),
								'value' => 1,
								'checked' => ($Vars->var['validada'] ? 'checked' : ''),
							));
							?>
						</label>

						<label>
							<button type="submit" class="btn btn-highlight"><i class="icon-filter"></i> <?php __e('Filtrar'); ?></button>
						</label>
					</fieldset>
				</form>
			</header>

            <?php if ($avistamentos) { ?>
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
			</div>
            <?php } else { ?>

            <div class="alert alert-info">
                <?php __e('Non se atoparon avistamentos con eses filtros.'); ?>
            </div>

            <?php } ?>

			<?php
			$Templates ->render('aux-paxinacion.php', array(
				'pagination' => $Data->pagination
			));
			?>
		</section>
	</div>
</section>
