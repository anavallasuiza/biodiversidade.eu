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
					<fieldset class="ly-e1">
						<h2><?php __e('Editores actuais'); ?></h2>

						<?php foreach ($proxecto['usuarios'] as $usuario) { ?>
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
								'value' => 'proxecto-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Quitar os seleccionados'))
							));
							?>
						</p>
					</fieldset>

					<?php if ($solicitudes) { ?>
					<fieldset class="ly-e1">
						<h2><?php __e('Solicitudes de participación'); ?></h2>

						<table class="simple">
							<thead>
								<tr>
									<th class="center"><?php __e('Aceptar'); ?></th>
									<th class="center"><?php __e('Rexeitar'); ?></th>
									<th><?php __e('Usuario'); ?></th>
								</tr>
							</thead>

							<tbody>
								<?php foreach ($solicitudes as $usuario) { ?>
								<tr>
									<td class="center"><input type="radio" name="solicitudes[<?php echo $usuario['nome']['url']; ?>]" value="1" /></td>
									<td class="center"><input type="radio" name="solicitudes[<?php echo $usuario['nome']['url']; ?>]" value="0" /></td>
									<td>
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
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>

						<p class="formulario-buttons">
							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'proxecto-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Proceder'))
							));
							?>
						</p>
					</fieldset>
					<?php } ?>

					<fieldset class="ly-e3">
						<h2><?php __e('Engadir novos editores'); ?></h2>

						<p class="formulario-field"><?php
							echo $Form->text(array(
								'name' => 'engadir',
								'placeholder' => __('Novos usuarios editores'),
								'class' => 'suggest',
								'data-url' => path('get-usuarios'),
								'data-search' => 'q',
								'multiple' => 'multiple'
						));
						?></p>

						<p class="formulario-buttons">
		                    <a href="<?php echo path('proxecto', $proxecto['url']); ?>" class="btn right">
		                        <i class="icon-arrow-left"></i> <?php __e('Voltar'); ?>
		                    </a>

							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'proxecto-editores',
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