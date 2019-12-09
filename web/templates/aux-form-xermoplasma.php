<?php defined('ANS') or die(); ?>

<fieldset>
	<div class="formulario-field">
		<label for="banco_xermoplasma"><?php __e('Banco de xermoplasma'); ?></label>
		<textarea id="banco_xermoplasma" name="avistamentos[banco_xeoplasma]"><?php echo $avistamentos['banco_xeoplasma']; ?></textarea>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-idcoleccion"><?php __e('Id colección'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[idcoleccion]',
                'id' => 'xermoplasma-idcoleccion'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-numero-colleita"><?php __e('Número de colleita'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[numero_colleita]',
                'id' => 'xermoplasma-numero-colleita'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-colector"><?php __e('Colector'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[colector]',
                'id' => 'xermoplasma-colector'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field-cols">
		<label><?php echo __('Voucher herbario') ?></label>

        <div>
            <p class="radio-group">
                <label><?php echo ('Si') ?>
                <input name="avistamentos[xermoplasma_herbario]" type="radio" value="1"></label>
    
                <label><?php echo ('Non') ?>
                <input name="avistamentos[xermoplasma_herbario]" type="radio" value="0"></label>
            </p>
    
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[xermoplasma_herbario_numero]',
                'label_text' => __('Nº')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-arquivo-gps"><?php __e('Arquivos GPS'); ?></label>
        <div>
            <?php
            echo $Form->file(array(
                'variable' => 'avistamentos[arquivo_gps]',
                'id' => 'xermoplasma-arquivo-gps',
                'data-text' => basename($avistamentos['arquivo_gps']),
                'data-value' => ($avistamentos['arquivo_gps'] ? fileWeb('uploads|'.$avistamentos['arquivo_gps']) : '')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-profundidade-auga"><?php __e('Profundidade auga (m)(acuáticas)'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[profundidade_auga]',
                'id' => 'xermoplasma-profundidade-auga'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-pendente"><?php __e('Pendente'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[pendente]',
                'id' => 'xermoplasma-pendente'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-orientacion"><?php __e('Orientacion'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[orientacion]',
                'id' => 'xermoplasma-orientacion'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-metodo-muestreo"><?php __e('Método de muestreo'); ?></label>
        <div>
            <?php
            echo $Form->select($enum['metodo_muestreo'], array(
                'variable' => 'avistamentos[metodo_muestreo]',
                'id' => 'xermoplasma-metodo-muestreo',
                'option_text_as_value' => true,
                'gettext' => true,
                'class' => 'w3',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-adultas-localizadas"><?php __e('Número de plantas adultas localizadas'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'options' => array('1-10', '10-50', '50-500', '>500'),
                'variable' => 'avistamentos[adultas_localizadas]',
                'id' => 'xermoplasma-adultas-localizadas',
                'option_text_as_value' => true,
                'class' => 'w2',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-aultas-mostreadas"><?php __e('Número de plantas adultas mostreadas'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'options' => array('1-10', '10-50', '50-500', '>500'),
                'variable' => 'avistamentos[adultas_mostreadas]',
                'id' => 'xermoplasma-aultas-mostreadas',
                'option_text_as_value' => true,
                'class' => 'w2',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
    
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-area-mostrada"><?php __e('Área mostreada aprox. (m2)'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[area_mostreada]',
                'id' => 'xermoplasma-area-mostrada'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-area-ocupacion"><?php __e('Área de ocupación aprox. (m2)'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[area_ocupacion]',
                'id' => 'xermoplasma-area-ocupacion'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-fenoloxia-froito"><?php __e('Fenoloxía do froito'); ?></label>
        <div>
            <?php
            echo $Form->select($enum['fenoloxia_froito'], array(
                'variable' => 'avistamentos[fenoloxia_froito]',
                'id' => 'xermoplasma-fenoloxia-froito',
                'option_text_as_value' => true,
                'gettext' => true,
                'class' => 'w3',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-procedencia-semente"><?php __e('Procedencia das sementes'); ?></label>
        <div>
            <?php
            echo $Form->select($enum['procedencia_semente'], array(
                'variable' => 'avistamentos[procedencia_semente]',
                'id' => 'xermoplasma-procedencia-semente',
                'option_text_as_value' => true,
                'gettext' => true,
                'class' => 'w3',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-tipo-vexetacion"><?php __e('Tipo de vexetación'); ?></label>
        <div>
            <?php
            echo $Form->select($enum['tipo_vexetacion'], array(
                'variable' => 'avistamentos[tipo_vexetacion]',
                'id' => 'xermoplasma-tipo-vexetacion',
                'option_text_as_value' => true,
                'gettext' => true,
                'class' => 'w3',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-textura-solo"><?php __e('Textura do solo'); ?></label>
        <div>
            <?php
            echo $Form->select($enum['textura_solo'], array(
                'variable' => 'avistamentos[textura_solo]',
                'id' => 'xermoplasma-textura-solo',
                'option_text_as_value' => true,
                'gettext' => true,
                'class' => 'w3',
                'first_option' => '',
                'data-placeholder' => __('Selección obrigatoria')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="xermoplasma-observacions"><?php echo __('Observacións') ?></label>
		
		<?php
		echo $Form->textarea(array(
			'variable' => 'avistamentos[observacions_xermoplasma]',
            'id' => 'xermoplasma-observacions'
		));
		?>
	</div>
</fieldset>
