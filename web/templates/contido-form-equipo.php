<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('equipos'); ?>"><?php echo __('Equipos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $equipo ? __('Edición do equipo') : __('Novo equipo'); ?></h2>
		</div>
	</header>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('equipo') ?></h1>

					<div class="formulario-field obrigatorio">
						<label for="equipos[titulo]"><?php __e('Nome do equipo'); ?></label>

						<div>
							<?php
							echo $Form->text(array(
								'variable' => 'equipos[titulo]',
								'id' => 'equipos[titulo]',
								'required' => 'required'
							));
							?>
						</div>
					</div>

					<div class="formulario-field obrigatorio">
						<label><?php __e('Descrición do equipo'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
								'variable' => 'equipos[texto]',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>

					<div class="formulario-field">
						<label><?php __e('Imaxe identificativa'); ?></label>

						<div>
							<?php
							echo $Form->file(array(
								'variable' => 'equipos[imaxe]',
								'id' => 'equipos[imaxe]',
								'data-text' => basename($equipo['imaxe']),
								'data-value' => ($equipo['imaxe'] ? fileWeb('uploads|'.$equipo['imaxe']) : '')
							));
							?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-equipos-contido'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($equipo['url'] && $Acl->check('action', 'equipo-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('equipo', $equipo['url']).get('phpcan_action', 'equipo-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'equipo-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $equipo ? path('equipo', $equipo['url']) : path('perfil'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
