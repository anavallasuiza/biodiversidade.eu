<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('catalogo'); ?>"><?php echo __('Catálogo'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo __('Novo avistamento') ?></h2>
		</div>
	</header>

	<div class="content wrapper">
		<form action="<?php echo path('editar', 'avistamento'); ?>" class="formulario-pisos custom-validation" method="post">
            <input type="hidden" name="especie" value="<?php echo $Vars->var['especie']; ?>" />

            <input type="hidden" name="nota" value="<?= $Vars->var['nota']; ?>" />

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Catalogación') ?></h1>

					<div class="formulario-field">
                        <label for="reino"><?php __e('Grupo'); ?></label>

                        <div>
                            <?php
                            echo $Form->select(array(
                                'variable' => 'grupo',
                                'options' => $grupos,
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'class' => 'w3',
                                'first_option' => __(''),
                                'data-placeholder' => __('Grupos'),
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-grupo'); ?></p>
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
    					'class' => 'btn btn-highlight',
    					'text' => ('<i class="icon-save"></i> '.__('Continuar'))
    				));
    				?>
                </p>
			</fieldset>
		</form>
	</div>
</section>
