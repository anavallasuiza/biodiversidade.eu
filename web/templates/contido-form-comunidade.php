<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('comunidade'); ?>"><?php echo __('Comunidade'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $comunidade['id'] ? __('Edición da ficha') : __('Nova ficha'); ?></h2>
		</div>
	</header>
    
    <?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Perfil') ?></h1>

					<div class="formulario-field obrigatorio">
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'comunidade[nome]',
                                'required' => 'required',
                                'label_text' => __('Nome')
                            ));
                            ?>
                        </div>
					</div>

                    <div class="formulario-field">
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'comunidade[telefono]',
                                'label_text' => __('Teléfono')
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'comunidade[correo]',
                                'label_text' => __('Correo electrónico')
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'comunidade[web]',
                                'label_text' => __('Web')
                            ));
                            ?>
                        </div>
                    </div>

					<div class="formulario-field obrigatorio">
                        <div>
                            <?php
                            echo $Form->textarea(array(
                                'variable' => 'comunidade[texto]',
                                'required' => 'required',
                                'class' => 'ckeditor',
                                'label_text' => __('Descrición da entidade')
                            ));
                            ?>
                        </div>
					</div>

                    <div class="formulario-field obrigatorio">
                        <div>
                            <?php
                            echo $Form->file(array(
                                'variable' => 'comunidade[logo]',
                                'required' => ($comunidade['id'] ? '' : 'required'),
                                'label_text' => __('Sube un logo desta entidade')
                            ));
                            ?>
                        </div>
                    </div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-comunidade-contido'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($comunidade['id'] && $Acl->check('action', 'comunidade-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('comunidade', $comunidade['url']).get('phpcan_action', 'comunidade-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'comunidade-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $comunidade ? path('comunidade', $comunidade['url']) : path('comunidade'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
