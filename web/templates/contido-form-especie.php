<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('catalogo'); ?>"><?php echo __('Catálogo'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php __e('Nova especie') ?></h2>
		</div>
	</header>

    <?php $Templates->render('aux-form-validation.php'); ?>

	<div id="confirmacion-especie" class="content wrapper formulario-pisos">
		<div class="ly-f1">
			<fieldset class="ly-e1">
				<h1><?php __e('Estás seguro de que non existe xa?') ?></h1>

				<p class="formulario-field">
					<?php
					echo $Form->text(array(
						'variable' => 'especies[url]',
						'label_text' => __('Busca primeiro nas especies do catálogo'),
						'class' => 'listaxe-especies w3',
						'id' => 'listaxe-especies-existentes',
                        'placeholder' => __('Nome da especie')
					));
					?>
				</p>
			</fieldset>

			<fieldset class="ly-e1 footer">
				<p class="formulario-buttons"><?php
				echo $Html->a(array(
					'href' => path('catalogo'),
					'class' => 'btn',
					'id' => 'xa-existe-especie',
					'text' => __('Pois parece que xa existía')
				));

				echo $Form->button(array(
					'type' => 'button',
					'class' => 'btn',
					'id' => 'non-existe-especie',
					'text' => __('Parece que non existe no catálogo e necesito dála de alta')
				));
				?></p>
			</fieldset>
		</div>
	</div>

	<div id="formulario-especie" class="content wrapper hidden nova-especie">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Catalogación') ?></h1>

					<div class="formulario-field obrigatorio">
                        <label for="reino"><?php __e('Grupo'); ?></label>

                        <div>
                            <?php
                            echo $Form->select(array(
                                'variable' => 'grupos',
                                'options' => $grupos,
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'id' => 'grupo',
                                'class' => 'w3 select-grupos',
                                'first_option' => __(''),
                                'data-placeholder' => __('Grupos'),
                                'data-child' => 'select.select-clases',
                                'required' => 'required',
                                'tabindex' => 1
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
						<label for="clase"><?php __e('Clase'); ?></label>

                        <div>
                            <?php
                            echo $Form->select(array(
                                'variable' => 'clases',
                                'id' => 'clase',
                                'class' => 'w3 select-clases',
                                'first_option' => __(''),
                                'data-placeholder' => __('Clases'),
                                'required' => 'required',
                                'data-sendatos' => __('O reino escollido non ten clases'),
                                'data-child' => 'select.select-ordes',
                                'tabindex' => 2
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
						<label for="orde"><?php __e('Ordes'); ?></label>

                        <div>
                            <?php
                            echo $Form->select(array(
                                'variable' => 'ordes',
                                'id' => 'orde',
                                'class' => 'w3 select-ordes',
                                'first_option' => __(''),
                                'data-placeholder' => __('Ordes'),
                                'required' => 'required',
                                'data-sendatos' => __('A clase escollida non ten ordes'),
                                'data-child' => 'select.select-familias',
                                'tabindex' => 3
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
						<label for="familia"><?php __e('Familia'); ?></label>

                        <div>
                            <div class="contenedor-nome">
                                <?php
                                echo $Form->select(array(
                                    'variable' => 'familias',
                                    'id' => 'familia',
                                    'class' => 'w3 select-familias',
                                    'first_option' => __(''),
                                    'data-placeholder' => __('Familias'),
                                    'required' => 'required',
                                    'data-sendatos' => __('A orde escollida non ten familias'),
                                    'data-child' => 'select.select-xeneros',
                                    'tabindex' => 4
                                ));
                                ?>
                            </div>

                            <div class="contenedor-autor">
                                <?php if (in_array('editor', arrayKeyValues($user['roles'], 'code'))) { ?>
                                <a href="<?php echo path('editar', 'familia'); ?>" class="btn modal-ajax"><?= __('Nova familia'); ?></a>
                                <?php } ?>
                            </div>
                        </div>

					</div>

					<div class="formulario-field obrigatorio">
						<label for="xenero"><?php __e('Xénero'); ?></label>

                        <div>
                            <div class="contenedor-nome">
                                <div id="listado-xeneros" class="hidden">
                                    <?php
                                    echo $Form->select(array(
                                        'variable' => 'xeneros',
                                        'id' => 'xenero',
                                        'class' => 'w3 select-xeneros',
                                        'first_option' => __(''),
                                        'data-placeholder' => __('Xeneros'),
                                        'required' => 'required',
                                        'data-sendatos' => __('A familia escollida non ten xéneros'),
                                        'tabindex' => 5
                                    ));
                                    ?>
                                </div>

                                <div id="listado-xeneros-completo">
                                    <?php
                                    echo $Form->text(array(
                                        'variable' => 'xeneros-completo',
                                        'id' => 'xenero-completo',
                                        'class' => 'w3 select-xeneros-completo',
                                        'placeholder' => __('Xeneros'),
                                        'tabindex' => 5
                                    ));
                                    ?>
                                </div>
                            </div>

                            <div class="contenedor-autor">
                                <?php if (in_array('editor', arrayKeyValues($user['roles'], 'code'))) { ?>
                                <a href="<?php echo path('editar', 'xenero'); ?>" class="btn modal-ajax"><?= __('Novo xénero'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
                    <p><strong><?php __e('Este contido será gardado en idioma %s', __('idioma-'.LANGUAGE)); ?></strong></p>
                    <br />
					<p><?php __e('axuda-formulario-especies-catalogacion'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Nomes') ?></h1>

					<div class="formulario-field nome-con-autor obrigatorio">
                        <label for="nome-especie">
                            <?php __e('Especie'); ?>
                        </label>
                        
                        <div>
                            <div class="contenedor-autor">
                                <?php
                                echo $Form->text(array(
                                    'id' => 'autor-especie',
                                    'variable' => 'especies[autor]',
                                    'class' => 'autor autocomplete',
                                    'placeholder' => __('Autor'),
                                    'required' => 'required',
                                    'data-url' => path('get-autores'),
                                    'tabindex' => 7
                                ));
                                ?>
                            </div>

                            <div class="contenedor-nome">
                                <?php
                                echo $Form->text(array(
                                    'id' => 'nome-especie',
                                    'variable' => 'especies[nome_cientifico]',
                                    'class' => 'autocomplete',
                                    'data-url' => path('get-nome-especies'),
                                    'placeholder' => __('Nome da especie'),
                                    'required' => 'required',
                                    'tabindex' => 6
                                ));
                                ?>
                            </div>
                        </div>
					</div>
                    
                    <div class="formulario-field nome-con-autor">
                        <label for="nome-subespecie">
                            <?php __e('Subespecie (opcional)'); ?>
                        </label>

                        <div>
                            <div class="contenedor-autor">
                                <?php
                                echo $Form->text(array(
                                    'id' => 'autor-subespecie',
                                    'variable' => 'especies[subespecie_autor]',
                                    'class' => 'autor autocomplete',
                                    'placeholder' => __('Autor'),
                                    'data-url' => path('get-autores'),
                                    'tabindex' => 9
                                ));
                                ?>
                            </div>

                            <div class="contenedor-nome">
                                <?php
                                echo $Form->text(array(
                                    'id' => 'nome-subespecie',
                                    'variable' => 'especies[subespecie]',
                                    'data-url' => path('get-nome-subespecies'),
                                    'class' => 'nome-subespecie autocomplete',
                                    'placeholder' => __('Nome da subespecie'),
                                    'tabindex' => 8
                                ));
                                ?>
                            </div>
                        </div>
					</div>
                    
                    <div class="formulario-field nome-con-autor">
                        <label for="nome-variedade">
                            <?php __e('Variedade (opcional)'); ?>
                        </label>

                        <div>
                            <div class="contenedor-autor">
                                <?php
                                echo $Form->text(array(
                                    'id' => 'autor-variedade',
                                    'variable' => 'especies[variedade_autor]',
                                    'class' => 'autor autocomplete',
                                    'placeholder' => __('Autor'),
                                    'data-url' => path('get-autores'),
                                    'tabindex' => 11
                                ));
                                ?>
                            </div>

                            <div class="contenedor-nome">
                                <?php
                                echo $Form->text(array(
                                    'id' => 'nome-variedade',
                                    'variable' => 'especies[variedade]',
                                    'class' => 'nome-variedade',
                                    'placeholder' => __('Nome da variedade'),
                                    'tabindex' => 10
                                ));
                                ?>
                            </div>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="nome-comun"><?php __e('Proposta nome común (opcional)'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'nome-comun',
                                'class' => 'select-nome-comun',
                                'variable' => 'especies[nome_comun]',
                                'placeholder' => __('Nomes comúns'),
                                'tabindex' => 12
                            ));
                            ?>
                        </div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-especies-nomes'); ?></p>
				</section>
			</div>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <a href="<?php echo path('catalogo'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>

                    <?php
    				echo $Form->button(array(
    					'type' => 'submit',
                        'id' => 'boton-gardar',
    					'name' => 'phpcan_action',
    					'value' => 'especie-gardar',
    					'class' => 'btn btn-highlight',
    					'text' => ('<i class="icon-save"></i> '.__('Gardar')),
                        'tabindex' => 13
    				));
    				?>
                </p>
			</fieldset>

            <div class="hidden overlay">
                <div class="overlay-background"></div>
                <div class="overlay-text">
                    <i class="icon-spinner icon-spin"></i> <?php __e('Validando'); ?>
                </div>
            </div>
		</form>
	</div>
</section>
