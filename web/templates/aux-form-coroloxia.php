<?php defined('ANS') or die(); ?>

<fieldset>
	<div class="formulario-field">
		<label for="coroloxia-distancia-umbral"><?php __e('Distancia umbral entre poboacións'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[distancia_umbral]',
                'id' => 'coroloxia-distancia-umbral'
            ));
            ?>
        </div>
	</div>					

	<div class="formulario-field">
		<label for="coroloxia-definicion-individuo"><?php __e('Definición do individuo'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[definicion_individuo]',
                'id' => 'coroloxia-definicion-individuo'
            ));
            ?>
        </div>
	</div>							

	<div class="formulario-field-cols">
		<label><?php echo __('Voucher herbario') ?></label>

        <div>
            <p class="radio-group">
                <label><?php echo ('Si') ?>
                <input name="avistamentos[coroloxia_herbario]" type="radio" value="1"></label>
    
                <label><?php echo ('Non') ?>
                <input name="avistamentos[coroloxia_herbario]" type="radio" value="0"></label>
            </p>
    
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[coroloxia_herbario_numero]',
                'label_text' => __('Nº')
            ));
            ?>
        </div>
	</div>
</fieldset>

<fieldset>
	<h1><?php echo __('Polígonos que limitan a poboación') ?></h1>

	<h2><?php echo __('Área de presenza') ?></h2>

	<div class="formulario-field">
		<label for="coroloxia-numero-exemplares"><?php __e('Nº de exemplares'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[numero_exemplares]',
                'id' => 'coroloxia-numero-exemplares'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="coroloxia-area-presencia-shapefile"><?php __e('Arquivo Shapefile'); ?></label>
        <div>
            <?php
            echo $Form->file(array(
                'variable' => 'avistamentos[area_presencia_shapefile]',
                'id' => 'coroloxia-area-presencia-shapefile',
                'data-text' => basename($avistamentos['area_presencia_shapefile']),
                'data-value' => ($avistamentos['area_presencia_shapefile'] ? fileWeb('uploads|'.$avistamentos['area_presencia_shapefile']) : '')
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field-cols">
		<label><?php echo __('Tipo de censo empregado') ?></label>

		<div>
			<label>
				<?php echo ('Censo directo') ?>
				<input name="avistamentos[tipo_censo]" type="radio" value="directo">
			</label>
			<label>
				<?php echo ('Censo por estima') ?>
				<input name="avistamentos[tipo_censo]" type="radio" value="estima">
			</label>
		</div>
	</div>

	<div class="formulario-field">	
		<label for="coroloxia-superficia-ocupacion"><?php __e('Superficie de ocupación'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[superficie_ocupacion]',
                'id' => 'coroloxia-superficia-ocupacion'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">	
		<label for="coroloxia-densidade-ocupacion"><?php __e('Densidade (individuos por m2)'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[densidade_ocupacion]',
                'id' => 'coroloxia-densidade-ocupacion'
            ));
            ?>
        </div>
	</div>

	<h2><?php echo __('Área de prioritaria') ?></h2>
	<div class="formulario-field">
		<label for="coroloxia-area-prioritaria"><?php __e('Arquivo Shapefile'); ?></label>
        <div>
            <?php
            echo $Form->file(array(
                'variable' => 'avistamentos[area_prioritaria]',
                'id' => 'coroloxia-area-prioritaria',
                'data-text' => basename($avistamentos['area_prioritaria']),
                'data-value' => ($avistamentos['area_prioritaria'] ? fileWeb('uploads|'.$avistamentos['area_prioritaria']) : '')
            ));
            ?>
        </div>
	</div>	

	<h2><?php echo __('Área de potencial'); ?></h2>

	<div class="formulario-field">
		<label for="coroloxia-area-potencial"><?php __e('Arquivo Shapefile'); ?></label>
        <div>
            <?php
            echo $Form->file(array(
                'variable' => 'avistamentos[area_potencial]',
                'id' => 'coroloxia-area-potencial',
                'data-text' => basename($avistamentos['area_potencial']),
                'data-value' => ($avistamentos['area_potencial'] ? fileWeb('uploads|'.$avistamentos['area_potencial']) : '')
            ));
            ?>
        </div>
	</div>
</fieldset>

<fieldset>
	<h1><?php echo __('Estima por cuadrículas UTM 500 x 500 m'); ?></h1>

	<h2><?php echo __('Cuadrícula UTM (centro da cuadrícula de 500 m2)'); ?></h2>

	<div class="formulario-field">
		<label for="coroloxia-superficie-mostreada"><?php __e('Superficie mostreada (m2)'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[superficie_mostreada]',
                'id' => 'coroloxia-superficie-mostreada'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">	
		<label for="coroloxia-individuos-contados"><?php __e('Número de individuos contados'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[individuos_contados]',
                'id' => 'coroloxia-individuos-contados'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="coroloxia-superficie-potencial"><?php __e('Superficie potencial do hábitat da cuadrícula'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[superficie_potencial]',
                'id' => 'coroloxia-superficie-potencial'
            ));
            ?>
        </div>
	</div>

	<div class="formulario-field">
		<label for="coroloxia-densidade"><?php __e('Densidade'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[densidade]',
                'id' => 'coroloxia-densidade'
            ));
            ?>
        </div>
	</div>
	<div class="formulario-field">
		<label for="coroloxia-individuos-estimados"><?php __e('Nº de individuos estimados'); ?></label>
        <div>
            <?php
            echo $Form->text(array(
                'variable' => 'avistamentos[individuos_estimados]',
                'id' => 'coroloxia-individuos-estimados'
            ));
            ?>
        </div>
	</div>
</fieldset>

<fieldset>
	<div class="formulario-field">
		<label for="coroloxia-observacions"><?php echo __('Observacións') ?></label>
        <div>
            <?php
            echo $Form->textarea(array(
                'variable' => 'avistamentos[observacions_coroloxia]',
                'id' => 'coroloxia-observacions'
            ));
            ?>
        </div>
	</div>	
</fieldset>
