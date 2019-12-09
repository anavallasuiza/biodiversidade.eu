<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('blogs'); ?>"><?php __e('Blogs e proxectos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $proxecto ? __('Edición do proxecto') : __('Novo proxecto'); ?></h2>
		</div>
	</header>

    <?php $Templates->render('aux-form-validation.php'); ?>
    
	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Título do proxecto'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'proxectos[titulo]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
                        <label for="intro"><?php __e('Pequena introdución'); ?></label>
                        <div>
                            <?php
                            echo $Form->textarea(array(
                                'id' => 'intro',
                                'variable' => 'proxectos[intro]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
						<label for="texto"><?php __e('Contido do proxecto'); ?></label>
						<div>
							<?php
							echo $Form->textarea(array(
                                'id' => 'texto',
								'variable' => 'proxectos[texto]',
								'class' => 'ckeditor',
                                'required' => 'required'
							));
							?>
						</div>
					</div>

                    <div class="formulario-field">
                        <label for="aberto"><?php __e('É un proxecto público?'); ?></label>
                        <div>
                            <input type="checkbox" id="aberto" name="proxectos[aberto]" <?php echo $Vars->var['proxectos']['aberto'] ? 'checked="checked"' : ''; ?> value="1" />
                        </div>
                    </div>

					<div class="formulario-field">
                        <label for="espazos"><?php __e('Espazos relacionados'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'espazos',
                                'variable' => 'espazos[url]',
                                'placeholder' => __('Selecciona un ou varios'),
                                'class' => 'suggest',
                                'data-url' => path('get-espazos'),
                                'data-search' => 'q',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['espazos'])),
                                'multiple' => 'multiple'
                            ));
                            ?>
                        </div>
                    </div>

					<div class="formulario-field">
                        <label for="ameazas"><?php __e('Ameazas relacionadas'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'ameazas',
                                'variable' => 'ameazas[url]',
                                'placeholder' => __('Selecciona unha ou varias'),
                                'class' => 'suggest',
                                'data-url' => path('get-ameazas'),
                                'data-search' => 'q',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['ameazas'])),
                                'multiple' => 'multiple'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="ameazas"><?php __e('Iniciativas de conservación'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'iniciativas',
                                'variable' => 'iniciativas[url]',
                                'placeholder' => __('Selecciona unha ou varias'),
                                'class' => 'suggest',
                                'data-url' => path('get-iniciativas'),
                                'data-search' => 'q',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['iniciativas'])),
                                'multiple' => 'multiple'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="especie"><?php __e('Especies'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'especie',
                                'variable' => 'especies[url]',
                                'placeholder' => __('Especies afectadas'),
                                'class' => 'listaxe-especies',
                                'data-multiple' => 'multiple',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['especies']))
                            ));
                            ?>
                        </div>
                    </div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-proxectos-contido'); ?></p>
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
					<p><?php echo __('axuda-formulario-novas-imaxes'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($proxecto['url'] && $Acl->check('action', 'proxecto-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('proxecto', $proxecto['url']).get('phpcan_action', 'proxecto-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

					<?php
					echo $Form->button(array(
						'type' => 'submit',
						'name' => 'phpcan_action',
						'value' => 'proxecto-gardar',
						'class' => 'btn btn-highlight',
						'text' => ('<i class="icon-save"></i> '.__('Gardar'))
					));
					?>

                    <a href="<?php echo $proxecto ? path('proxecto', $proxecto['url']) : path('blogs'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>
	</div>
</section>
