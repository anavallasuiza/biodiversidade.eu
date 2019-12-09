<?php defined('ANS') or die(); ?>
<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php echo __('Previsualización de datos'); ?></h1>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent">

        	<nav>
        		<a href="<?php echo path('importar-observacions'); ?>">
        			<i class="icon-angle-left"></i> <?php __e('Seleccionar outro arquivo'); ?>
        		</a>
        	</nav>

        	<?php if ($puntosInvalidos) { ?>
        	<p class="warning-importacion">
        		<i class="icon-warning-sign"></i> <?php __e('Detectáronse puntos con coordenadas non válidas nalgunhas das filas do arquivo, por favor revisaas e asegúrate de que teña al menos un xogo de coordenadas correctas, ben MGRS, ben UTM ou ben latitude/lonxitude.'); ?>
        	</p>
        	<?php } ?>

			<form id="tabla-observacions" action="<?php echo path('importar-observacions', 'resultado'); ?>" method="post" class="form-datatable">
			    <table class="datatable">
			    	<colgroup>
			    		<col class="estado" />
			    		<col class="ver" />
			    		<col class="nome" />
			    		<col class="especie" />
			    		<col class="Sinonimos" />
			    		<col class="procesado" />
			    	</colgroup>
			        <thead>
			            <tr>
			            	<th title="<?php __e('Estado'); ?>"><?php __e('E.'); ?></th>
			            	<th><?php __e('Ver'); ?></th>
			                <th><?php __e('Nome'); ?></th>
			                <th><?php __e('Especie'); ?></th>
			                <th><?php __e('Sinónimos'); ?></th>
			            </tr>
			        </thead>

			        <tbody>
			            <?php foreach ($resultado as $code => $item) { ?>
			            <tr class="<?php echo $item['status']; ?> <?php echo $item['puntosInvalidos'] ? 'invalid-points': ''; ?>" title="<?php echo $item['message']; ?>">
			            	<td>
			            		<?php if ($item['puntosInvalidos']) { ?>
			            		<i class="icon-remove-circle"></i>
			            		<?php } else { ?>
			                	<i class="<?php echo $item['status'] === 'new' ? 'icon-plus' : ($item['status'] === 'unknown' ? 'icon-question': 'icon-ok') ; ?>"></i>
			                	<?php } ?>
			                </td>
			                <td class="ver">
			                	<i class="icon-caret-right"></i>
			                </td>
			                <td class="nome-arquivo">
			                	<label for="especies[<?php echo $code; ?>]">
			                		<?php echo $item['nome']; ?>
			                	</label>
			                </td>
			                <td class="especie">
		                		<?php if (count($item['especie'])) { ?>
		                		<a href="#" class="btn right cambiar-opcion" data-text="<i class='icon-remove'></i> <?php __e('Cancelar');?>">
		                			<i class="icon-search"></i> <?php __e('Outras'); ?>
		                		</a>
		                		<div class="opcion-por-defecto">
		                			<?php if (count($item['especie']) === 1) { ?>

		                			<span>
			                			<?php echo $item['especie'][0]['nome']; ?>
			                		</span>
			                		<input type="hidden" class="selector-especie" id="especie[<?php echo $code; ?>][url]" name="especie[<?php echo $code; ?>][url]" value="<?php echo $item['especie'][0]['url']; ?>"/>

		                			<?php } else { ?>
		                			
		                			<select id="especie-<?php echo $code; ?>" name="especie[<?php echo $code; ?>][url]" class="selector-especie" placeholder="<?php __e('Selecciona una especie'); ?>">
			                			<option></option>
			                			<option value="especie-nova"><?php __e('Nova especie'); ?></option>
			                			<?php foreach($item['especie'] as $especie) { ?>
			                			<option value="<?php echo $especie['url']; ?>"><?php echo $especie['nome']; ?></option>
			                			<?php } ?>
			                		</select>

		                			<?php } ?>
		                		</div>
		                		<?php } ?>

		                		<div class="listaxe-total-especies <?php echo count($item['especie']) > 0 ? 'hidden': ''; ?>">
			                		<input type="text" name="especie[<?php echo $code; ?>][url]" id="especie[<?php echo str_replace('-', '', $code); ?>][url]" class="listaxe-especies" placeholder="<?php __e('Selecciona una especie'); ?>" <?php echo count($item['especie']) > 0 ? 'disabled': ''; ?>/>
			                	</div>
			                </td>
			                <td class="sinonimos">
			                	<div class="<?php /*echo !$item['especie'] ? 'hidden': '';*/ ?>">
				                	<label>
				                		<span><?php __e('Empregar nome do arquivo como '); ?></span>
				                		<select name="especie[<?php echo $code; ?>][sinonimo]">
				                			<option value=""><?php __e('Nada'); ?></option>
				                			<option value="nome"><?php __e('Nome real'); ?></option>
				                			<option value="sinonimo"><?php __e('Sinónimo'); ?></option>
				                		</select>
				                	</label>
				                </div>
			                </td>
			            </tr>
			            
			            <tr class="fila-fichas">
			            	<td colspan="6">
			            		<table class="taboa-fichas">
			            			<thead>
			            				<tr>
			            					<th><?php __e('Ver'); ?></th>
			            					<th><?php __e('Checksum'); ?></th>
			            					<th><?php __e('Localidade'); ?></th>
			            					<th><?php __e('Dato'); ?></th>
			            					<th><?php __e('Arquivo'); ?></th>
			            					<th><?php __e('Tipo'); ?></th>
			            					<th><?php __e('Referencia'); ?></th>
			            					<th><?php __e('Fornecedor'); ?></th>
			            					<th><?php __e('Existe'); ?></th>
                                            <th><?php __e('Observacións'); ?></th>
                                            <th><?php __e('Data avistamento'); ?></th>
                                            <th><?php __e('Outros observadores'); ?></th>
                                            <th><?php __e('Sexo'); ?></th>
                                            <th><?php __e('Fase ou estadío'); ?></th>
                                            <th><?php __e('Comportamento migratorio'); ?></th>
                                            <?php /*<th><?php __e('Especies acompañantes'); ?></th> */ ?>
			            				</tr>
			            			</thead>
			            			<tbody>
			            				<?php foreach ($item['fichas'] as $code => $ficha) { ?>
			            				<tr class="<?php echo $ficha['exists'] ? 'existe': ''; ?> <?php echo $ficha['puntosInvalidos'] ? 'invalid-points': ''; ?>">
			            					<td class="ver"><i class="icon-caret-right"></i></td>
			            					<td><?php echo $ficha['checksum']; ?></td>
			            					<td><?php echo $ficha['localidade']; ?></td>
			            					<td><?php echo $ficha['tipo']; ?></td>
			            					<td><?php echo $ficha['arquivo']; ?></td>
			            					<td><?php echo $ficha['tipo_referencia']; ?></td>
			            					<td><?php echo $ficha['referencia']; ?></td>
			            					<td><?php echo $ficha['fornecedor']; ?></td>
			            					<td><?php echo $ficha['exists'] ? __('Sí') : __('Non'); ?></td>
                                            <td><?php echo $ficha['observacions']; ?></td>
                                            <td><?php echo $ficha['data_observacion']; ?></td>
                                            <td><?php echo $ficha['outros_observadores']; ?></td>
                                            <td>
                                                <select id="sexo-especie-<?php echo $code; ?>" name="especie[<?php echo $code; ?>][sexo]" style="width: 110px;" placeholder="<?php __e('Selecciona unha opción'); ?>">
                                                    <option value=""></option>
                                                    <?php foreach($ficha['opcions_sexo'] as $opcionSexo) { ?>
                                                    <option value="<?php echo $opcionSexo; ?>"<?= $opcionSexo == $ficha['sexo'] ? ' selected="selected"' : ''; ?>><?php echo $opcionSexo; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select id="fase-especie-<?php echo $code; ?>" name="especie[<?php echo $code; ?>][fase]" style="width: 110px;" placeholder="<?php __e('Selecciona unha opción'); ?>">
                                                    <option value=""></option>
                                                    <?php foreach($ficha['opcions_fase'] as $opcionFase) { ?>
                                                    <option value="<?php echo $opcionFase; ?>"<?= $opcionFase == $ficha['fase'] ? ' selected="selected"' : ''; ?>><?php echo $opcionFase; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select id="migracion-especie-<?php echo $code; ?>" name="especie[<?php echo $code; ?>][migracion]" style="width: 110px;" placeholder="<?php __e('Selecciona unha opción'); ?>">
                                                    <option value=""></option>
                                                    <?php foreach($ficha['opcions_migracion'] as $opcionMigracion) { ?>
                                                    <option value="<?php echo $opcionMigracion; ?>"<?= $opcionMigracion == $ficha['migracion'] ? ' selected="selected"' : ''; ?>><?php echo $opcionMigracion; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <?php /*<td><?php echo $ficha['especies_acompanhantes']; ?></td>*/ ?>
					            		</tr>
					            		<tr class="fila-puntos">
					            			<td colspan="9">
					            				<table class="taboa-puntos">
					            					<thead>
						            					<th><?php __e('MGRS'); ?></th>
						            					<th><?php __e('UTM'); ?></th>
						            					<th><?php __e('Lat/Lng'); ?></th>
						            					<th><?php __e('Datum'); ?></th>
					            					</thead>
					            					<tbody>
							            				<?php foreach ($ficha['puntos'] as $punto) { ?>
							            				<tr class="<?php echo $punto['valido'] ? '': 'invalid-points'; ?>">
							            					<td><?php echo $punto['mgrs']; ?></td>
							            					<td><?php echo $punto['fuso'] . ' ' . $punto['utm_x'] . ' ' . $punto['utm_y']; ?></td>
							            					<td><?php echo $punto['lat'] || $punto['long'] ? $punto['lat'] . ', ' . $punto['long'] : ''; ?></td>
							            					<td><?php echo $punto['datum']; ?></td>
							            				</tr>
							            				<?php } ?>
							            			</tbody>
							            		</table>
					            			</td>
					            		</tr>
			            				<?php } ?>
			            			</tbody>
			            		</table>
			            	</td>
			            </tr>
			            <?php } ?>
			    </table>

			    <textarea name="csv" class="hidden"><?php echo base64_encode(serialize($resultado)); ?></textarea>

			    <div class="accions">
			    	<button type="submit" name="phpcan_action" value="importacion-observacions-gardar" class="btn">
			    		<?php __e('Gardar'); ?>
			    	</button>
			    </div>
			</form>
		</section>
	</div>
</section>