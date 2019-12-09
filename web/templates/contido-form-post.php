<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('blogs'); ?>"><?php echo __('Blogues'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $post ? __('Edición do post') : __('Novo post en %s', $blog['titulo']); ?></h2>
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
                                'variable' => 'posts[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
						<label for="texto"><?php __e('Contido do post'); ?></label>
						<div>
							<?php
							echo $Form->textarea(array(
                                'id' => 'texto',
								'variable' => 'posts[texto]',
								'class' => 'ckeditor',
                                'required' => 'required'
							));
							?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-posts-contido'); ?></p>
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
					<p><?php echo __('axuda-formulario-posts-imaxes'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($post['url'] && $Acl->check('action', 'post-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('post', $blog['url'], $post['url']).get('phpcan_action', 'post-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'post-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $post ? path('post', $blog['url'], $post['url']) : path('blog', $blog['url']); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
