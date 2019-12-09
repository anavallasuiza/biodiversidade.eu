<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('eventos'); ?>"><?php echo __('Novas e eventos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $evento ? __('Edición do evento') : __('Novo evento'); ?></h2>
		</div>
	</header>
    
    <?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Evento') ?></h1>

					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Título do evento'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'eventos[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
                        <label for="lugar"><?php __e('Lugar do evento'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'lugar',
                                'variable' => 'eventos[lugar]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
                        <label for=""><?php __e('Data do evento'); ?></label>
                        <div>
						  <?php
                            echo $Form->text(array(
                                'id' => 'data',
                                'variable' => 'eventos[data]',
                                'placeholder' => date('d-m-Y H:i'),
                                'class' => 'datetimepicker',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
						<label for="texto"><?php __e('Descrición do evento'); ?></label>
						<div>
							<?php
							echo $Form->textarea(array(
                                'id' => 'texto',
								'variable' => 'eventos[texto]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
                    <p><strong><?php __e('Este contido será gardado en idioma %s', __('idioma-'.LANGUAGE)); ?></strong></p>
                    <br />
					<p><?php echo __('axuda-formulario-eventos-contido'); ?></p>
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

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<?php
					$Templates->render('aux-form-imaxes.php', array(
						'imaxes' => $imaxes,
						'licenzas' => $licenzas
					));
					?>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-eventos-imaxes'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($evento['url'] && $Acl->check('action', 'evento-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('evento', $evento['url']).get('phpcan_action', 'evento-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'evento-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $evento ? path('evento', $evento['url']) : path('eventos'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
