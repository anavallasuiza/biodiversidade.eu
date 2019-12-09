<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('proxectos'); ?>"><?php __e('Blogs e proxectos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $caderno ? __('Edición do caderno') : __('Novo caderno en %s', $proxecto['titulo']); ?></h2>
		</div>
	</header>

    <?php $Templates->render('aux-form-validation.php'); ?>
    
	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation"  method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Título'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'cadernos[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>			

					<div class="formulario-field obrigatorio">
						<label for="texto"><?php __e('Contido do caderno'); ?></label>
						<div>
							<?php
							echo $Form->textarea(array(
                                'id' => 'texto',
								'variable' => 'cadernos[texto]',
								'class' => 'ckeditor',
                                'required' => 'required'
							));
							?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-cadernos-contido'); ?></p>
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
					<p><?php echo __('axuda-formulario-cadernos-imaxes'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($caderno['url'] && $Acl->check('action', 'caderno-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('caderno', $proxecto['url'], $caderno['url']).get('phpcan_action', 'caderno-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'caderno-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $caderno ? path('caderno', $proxecto['url'], $caderno['url']) : path('proxecto', $proxecto['url']); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
