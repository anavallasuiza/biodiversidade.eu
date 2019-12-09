<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Engadir membros'); ?></h1>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent">
			<form action="<?php echo path(); ?>" class="formulario" method="post">
				<div class="ly-111">
					<fieldset class="ly-e3">
						<h2><?php __e('Membros actuais'); ?></h2>

						<?php foreach ($equipo['usuarios'] as $usuario) { ?>
						<p>
							<label>
								<?php if ($usuario['id'] === $user['id']) { ?>
								<input type="checkbox" disabled="disabled" readonly="readonly" />
								<?php } else { ?>
								<input type="checkbox" name="quitar[]" value="<?php echo $usuario['nome']['url']; ?>" />
								<?php } ?>

								<?php
								echo $Html->img(array(
									'src' => $usuario['avatar'],
									'alt' => $usuario['nome']['title'],
									'width' => 30,
									'height' => 30,
									'transform' => 'zoomCrop,30,30'
								));

								echo ' '.$usuario['nome']['title'].' '.$usuario['apelido1'];
								?>
							</label>
						</p>
						<?php } ?>

						<p class="formulario-buttons">
							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'equipo-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Quitar os seleccionados'))
							));
							?>
						</p>
					</fieldset>

					<fieldset class="ly-e3">
						<?php if ($solicitudes) { ?>
						<h2><?php __e('Solicitudes de membresía'); ?></h2>

						<?php foreach ($solicitudes as $usuario) { ?>
						<p>
							<label>
								<input type="checkbox" name="aceptar[]" value="<?php echo $usuario['nome']['url']; ?>" />

								<?php
								echo $Html->img(array(
									'src' => $usuario['avatar'],
									'alt' => $usuario['nome']['title'],
									'width' => 30,
									'height' => 30,
									'transform' => 'zoomCrop,30,30'
								));

								echo ' '.$usuario['nome']['title'].' '.$usuario['apelido1'];
								?>
							</label>
						</p>
						<?php } ?>

						<p class="formulario-buttons">
							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'equipo-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Aceptar os seleccionados'))
							));
							?>
						</p>

						<?php foreach ($solicitudes as $usuario) { ?>
						<p>
							<label>
								<input type="checkbox" name="rexeitar[]" value="<?php echo $usuario['nome']['url']; ?>" />

								<?php
								echo $Html->img(array(
									'src' => $usuario['avatar'],
									'alt' => $usuario['nome']['title'],
									'width' => 30,
									'height' => 30,
									'transform' => 'zoomCrop,30,30'
								));

								echo ' '.$usuario['nome']['title'].' '.$usuario['apelido1'];
								?>
							</label>
						</p>
						<?php } ?>

						<p class="formulario-buttons">
							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'equipo-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Rexeitar os seleccionados'))
							));
							?>
						</p>
						<?php } ?>
					</fieldset>

					<fieldset class="ly-e3">
						<h2><?php __e('Engadir novos membros'); ?></h2>

						<p class="formulario-field"><?php
							echo $Form->text(array(
								'name' => 'engadir',
								'placeholder' => __('Novos usuarios membros'),
								'class' => 'suggest',
								'data-url' => path('get-usuarios'),
								'data-search' => 'q',
								'multiple' => 'multiple'
						));
						?></p>

						<p class="formulario-buttons">
		                    <a href="<?php echo path('equipo', $equipo['url']); ?>" class="btn right">
		                        <i class="icon-arrow-left"></i> <?php __e('Voltar'); ?>
		                    </a>

							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'equipo-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Gardar'))
							));
							?>
						</p>
					</fieldset>
				</div>
			</form>
		</section>
	</div>
</section>