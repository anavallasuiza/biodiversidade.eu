<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('espazos'); ?>"><?php echo __('espazos e espazo'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $espazo ? __('Editar espazo') : __('Novo espazo'); ?></h2>
		</div>
	</header>

    <?php $Templates->render('aux-form-validation.php'); ?>
    
	<div class="content wrapper novo-espazo">
		<form id="form-espazo" action="<?php echo path(); ?>" class="formulario-pisos custom-validation" <?php echo $shapes['markers'] ? 'data-markers="' . $shapes['markers']  . '"': ''; ?>  <?php echo $shapes['polygons'] ? 'data-polygons="' . $shapes['polygons'] . '"': ''; ?> <?php echo $shapes['polylines'] ? 'data-polylines="' . $shapes['polylines'] . '"': ''; ?> method="post" enctype="multipart/form-data">
			<fieldset class="content wrapper ly-f1">
                <div class="content-mapa">
                    <div id="mapa" class="mapa"></div>
                    <div id="mapa-toolbar-top" class="toolbar-mapa">                            
                        <button id="fullscreen" class="btn fullscreen right" type="button">
                            <i class="icon-fullscreen"></i>
                        </button>
                        
                        <button id="zoom-plus" type="button" tabindex="-1" class="btn zoom" type="button">
                            <i class="icon-plus"></i>
                        </button>
                        
                        <button id="zoom-minus" type="button" tabindex="-1" class="btn zoom" type="button">
                            <i class="icon-minus"></i>
                        </button>
                        
                        <label>
                            <div class="desplegable w3" id="map-type" data-value="mapa">
                                <i class="icon-caret-down right"></i> <span><?php __e('Mapa')?></span>
                                <ul class="hidden" tabindex="-1">
                                    <li data-value="mapa"><?php __e('Mapa'); ?></li>
                                    <li data-value="satelite"><?php __e('Satelite'); ?></li>
                                    <li data-value="relieve"><?php __e('Relieve'); ?></li>
                                    <li data-value="hybrid"><?php __e('Mapa e satélite'); ?></li>
                                </ul>
                            </div>
                        </label>
                        
                        <section class="toolbar-options">
                            <label>
                                <span><?php __e('Ver etiquetas'); ?></span>
                                <input type="checkbox" id="toggle-labels"/>
                            </label>
                        </section>
                        
                        <button id="xeolocalizame" class="btn xeo" type="button">
                            <i class="icon-screenshot"></i> <?php __e('Xeolocalizaeme'); ?>
                        </button>
                        
                        <span class="separator"></span>
                        
                        <section class="toolbar-options drawing-options">
                            <button id="drawing-default" class="btn pressed" type="button">
                                <i class="icon-hand-up"></i> <?php __e('Seleccionar'); ?>
                            </button>
                            <button id="drawing-delete" class="btn" disabled="disabled" type="button">
                                <i class="icon-remove"></i> <?php __e('Borrar'); ?>
                            </button>
                            <?php foreach ($tiposPois as $tipo) { ?>
                            <button id="drawing-marker-<?php echo $tipo['url']; ?>" class="btn btn-marker" type="button" data-code="<?php echo $tipo['url']; ?>" data-icon="<?php echo fileWeb('uploads|' . $tipo['imaxe'], false, true); ?>">
                                <?php echo $tipo['nome']; ; ?>
                            </button>
                            <?php } ?>
                            <button id="drawing-polygon" class="btn" type="button">
                                <?php __e('Polígono'); ?>
                            </button>
                        </section>
                    </div>
                </div>
                
                <div class="formulario-field">
                    <label for="kml"><?php __e('Arquivo kml'); ?></label>
                    <div>
                        <?php
                        echo $Form->file(array(
                            'variable' => 'espazo[kml]',
                            'id' => 'kml',
                            'data-text' => basename($espazo['kml']),
                            'data-value' => ($espazo['kml'] ? fileWeb('uploads|'  .$espazo['kml'], false, true) : '')
                        ));
                        ?>
                    </div>
                </div>

                <div id="poi-form" class="hidden poi-form">
                    <header>
                        <h1><?php __e('POI'); ?></h1>
                    </header>
                    <div>
                        <p>
                            <label><?php __e('Nome'); ?></label>
                            <input type="text" class="poi-nome" name="nome"/>
                        </p>
                        <p>
                            <label><?php __e('Texto'); ?></label>
                            <textarea class="poi-texto" name="poi-texto"></textarea>
                        </p>
                    </div>
                </div>
			</fieldset>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php echo __('Espazo') ?></h1>

					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Nome do espazo'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'espazo[titulo]',
                                'id' => 'titulo',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
						<label for="descricion"><?php __e('Descrición'); ?></label>

						<div>
							<?php
							echo $Form->textarea(array(
                                'id' => 'descricion',
								'variable' => 'espazo[texto]',
                                'required' => 'required'
							));
							?>
						</div>
					</div>

					<div class="formulario-field obrigatorio">
                        <label for="lugar"><?php __e('Lugar'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'lugar',
                                'variable' => 'espazo[lugar]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="territorios"><?php __e('Zona'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($territorios, array(
                                'variable' => 'territorios',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'placeholder' => __('Lista de zonas'),
                                'first_option' => ''
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="espazos-tipos"><?php __e('Tipo de espazo'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($tipos, array(
                                'variable' => 'espazos_tipos',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'id' => 'espazos-tipos',
                                'placeholder' => __('Lista de tipos de espazos'),
                                'first_option' => ''
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="espazos-figuras"><?php __e('Figura de protección'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($figuras, array(
                                'variable' => 'espazos_figuras',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'id' => 'espazos-figuras',
                                'placeholder' => __('Lista de figuras de proteccion'),
                                'first_option' => ''
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
                                'data-values' => $especies
                            ));
                            ?>
                        </div>
                    </div>
				</fieldset>

				<section class="erros-axuda ly-e2">
                    <p><strong><?php __e('Este contido será gardado en idioma %s', __('idioma-'.LANGUAGE)); ?></strong></p>
                    <br />
					<p><?php echo __('axuda-formulario-espazos-contido'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<?php
					$Templates->render('aux-form-imaxes.php', array(
						'imaxes' => $imaxes,
						'licenzas' => $licenzas,
						'imaxes_tipos' => $imaxes_tipos
					));
					?>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php echo __('axuda-formulario-espazos-imaxes'); ?></p>
				</section>
			</div>
            
            
            
            <?php if ($Acl->check('action', 'espazo-validar')) { ?>
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field">
                        <label for="espazo-validado"><?php __e('Espazo validado'); ?></label>
						<div>
							<?php
							echo $Form->checkbox(array(
								'variable' => 'espazo[validado]',
								'value' => '1',
								'checked' => ($espazo['validado'] ? true : false),
								'force' => false
							));
							?>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-validar'); ?></p>
				</section>
			</div>
			<?php } ?>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($espazo['url'] && $Acl->check('action', 'espazo-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('espazo', $espazo['url']).get('phpcan_action', 'espazo-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

                    <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'name' => 'phpcan_action',
                        'value' => 'espazo-gardar',
                        'class' => 'btn btn-highlight',
                        'text' => ('<i class="icon-save"></i> '.__('Gardar'))
                    ));
                    ?>

                    <a href="<?php echo $espazo ? path('espazo', $espazo['url']) : path('espazos'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
				</p>
			</fieldset>
		</form>		
	</div>
</section>

<div class="poi-info-window hidden">
	<form class="form-infowindow">
		<fieldset>
			<p class="formulario-field">
				<?php
				echo $Form->text(array(
					'variable' => '',
					'label_text' => __('Título'),
					'required' => 'required'
				));
				?>
			</p>

			<p class="formulario-field">
				<?php
				echo $Form->textarea(array(
					'variable' => '',
					'label_text' => __('Descrición'),
					'required' => 'required'
				));
				?>
			</p>

			<p class="formulario-field">
				<label>
					<?php __e('Tipo'); ?>
					<select class="w3" required placeholder="<?php __e('Selección obrigatoria'); ?>">
						<option><option>
						<?php foreach($poit_tipos as $tipo) { ?>
						<option value="<?php echo $tipo['url']; ?>"><?php echo $tipo['nome']; ?></option>
						<?php } ?>
					</select>
				</label>
			</p>
		</fieldset>

		<fieldset class="footer">
			<?php
			echo $Form->button(array(
				'type' => '',
				'name' => 'phpcan_action',
				'value' => '',
				'class' => 'btn',
				'text' => ('<i class="icon-trash"></i> '.__('Cancelar'))
			));

			echo $Form->button(array(
				'type' => '',
				'name' => 'phpcan_action',
				'value' => '',
				'class' => 'btn btn-highlight',
				'text' => ('<i class="icon-save"></i> '.__('Gardar'))
			));
			?>
		</fieldset>
	</form>
</div>
