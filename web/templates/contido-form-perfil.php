<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('comunidade'); ?>"><?php echo __('Comunidade'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $blog ? __('Edición do perfil') : __('Novo perfil'); ?></h2>
		</div>
	</header>
    
    <?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Perfil') ?></h1>

					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Nome'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'blogs[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="titulo"><?php __e('Web'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'blogs[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>						

					<div class="formulario-field">
                        <label for="titulo"><?php __e('Contacto'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'blogs[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>							

					<div class="formulario-field obrigatorio">
                        <label for="texto"><?php __e('Descrición'); ?></label>
                        <div>
                            <?php
                            echo $Form->textarea(array(
                                'id' => 'texto',
                                'class' => 'ckeditor',
                                'variable' => 'blogs[texto]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-perfil-contido'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Logo') ?></h1>

					<div class="formulario-field">
						<label for="imaxe"><?php __e('Sube unha imaxe'); ?></label>
						<div>
                            <?php
                            echo $Form->file(array(
                                'id' => 'imaxe',
                                'variable' => 'blogs[imaxe]',
                                'required' => ($blog ? '' : 'required'),
                                'data-text' => basename($blog['imaxe']),
                                'data-value' => ($blog['imaxe'] ? fileWeb('uploads|'.$blog['imaxe']) : '')
                            ));
                            ?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-perfil-imaxes'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($blog['url'] && $Acl->check('action', 'blog-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('blog', $blog['url']).get('phpcan_action', 'blog-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'blog-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $blog ? path('blog', $blog['url']) : path('blogs'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
