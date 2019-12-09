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
				<div class="ly-11">
					<fieldset class="ly-e1">
						<h2><?php __e('Editores actuais'); ?></h2>

						<?php foreach ($blog['usuarios_autor'] as $usuario) { ?>
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
								'value' => 'blog-editores',
								'class' => 'btn btn-highlight',
								'text' => __('<i class="icon-save"></i> '.__('Quitar os seleccionados'))
							));
							?>
						</p>
					</fieldset>

					<fieldset class="ly-e2">
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
		                    <a href="<?php echo path('blog', $blog['url']); ?>" class="btn right">
		                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
		                    </a>

							<?php
							echo $Form->button(array(
								'type' => 'submit',
								'name' => 'phpcan_action',
								'value' => 'blog-editores',
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