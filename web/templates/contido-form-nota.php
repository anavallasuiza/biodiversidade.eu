<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('perfil'); ?>"><?php __e('As miñas notas'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php __e('Edición da nota'); ?></h2>
		</div>
	</header>

    <?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Título'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'notas[titulo]',
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
								'variable' => 'notas[texto]',
								'class' => 'ckeditor',
                                'required' => 'required'
							));
							?>
						</div>
					</div>
				</fieldset>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <a class="btn eliminar" href="<?php echo path('nota', $nota['url']).get('phpcan_action', 'nota-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'nota-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo path('nota', $nota['url']); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
