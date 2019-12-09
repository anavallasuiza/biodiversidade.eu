<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('actividades-didacticas'); ?>#actividades"><?php echo __('Documentación didáctica'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $didactica ? __('Edición da actividade didactica') : __('Nova actividade didactica'); ?></h2>
		</div>
	</header>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field obligatorio">
						<label for="didacticas[titulo]"><?php __e('Título da actividade didactica'); ?></label>

						<div>
							<?php
							echo $Form->text(array(
								'id' => 'didacticas[titulo]',
								'variable' => 'didacticas[titulo]',
								'required' => 'required'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[intro]"><?php __e('Pequena descrición do contido'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[intro]',
								'variable' => 'didacticas[intro]'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[xustificacion]"><?php __e('Xustificación'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[xustificacion]',
								'variable' => 'didacticas[xustificacion]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[desenvolvemento]"><?php __e('Desenvolvemento'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[desenvolvemento]',
								'variable' => 'didacticas[desenvolvemento]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[obxectivos]"><?php __e('Obxectivos'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[obxectivos]',
								'variable' => 'didacticas[obxectivos]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[competencias]"><?php __e('Competencias'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[competencias]',
								'variable' => 'didacticas[competencias]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[duracion]"><?php __e('Duración aproximada'); ?></label>

						<div>
							<?php
							echo $Form->text(array(
								'id' => 'didacticas[duracion]',
								'variable' => 'didacticas[duracion]'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[material]"><?php __e('Material necesario'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[material]',
								'variable' => 'didacticas[material]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label for="didacticas[recursos]"><?php __e('Recursos dispoñibles'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'id' => 'didacticas[recursos]',
								'variable' => 'didacticas[recursos]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
                    <p><strong><?php __e('Este contido será gardado en idioma %s', __('idioma-'.LANGUAGE)); ?></strong></p>
                    <br />
					<p><?php __e('axuda-formulario-didacticas-contido'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<?php
					$Templates->render('aux-form-adxuntos.php', array(
						'adxuntos' => $adxuntos
					));
					?>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-eventos-adxuntos'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<div class="formulario-buttons">
                    <a href="<?php echo $didactica ? path('didactica', $didactica['url']) : path('didactica'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'didactica-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>
				</div>
			</fieldset>
		</form>
	</div>
</section>
