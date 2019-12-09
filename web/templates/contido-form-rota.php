<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo $rota ? path('rota', $rota['url']) : path('rotas'); ?>"><?php echo __('Rotas e espazos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $rota ? __('Edición da rota') : __('Nova rota') ?></h2>
		</div>
	</header>

    <?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper">
		<form id="form-rota" action="<?php echo path(); ?>" class="formulario-pisos custom-validation" <?php echo $shapes['markers'] ? 'data-markers="' . $shapes['markers']  . '"': ''; ?>  <?php echo $shapes['polygons'] ? 'data-polygons="' . $shapes['polygons'] . '"': ''; ?> <?php echo $shapes['polylines'] ? 'data-polylines="' . $shapes['polylines'] . '"': ''; ?> method="post" enctype="multipart/form-data">
            
            <div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Nome rota'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'rotas[titulo]',
                                'placeholder' => _('Nome da rota'),
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
                        <label for="descricion"><?php __e('Descrición da rota'); ?></label>
                        <div>
                            <?php
                            echo $Form->textarea(array(
                                'id' => 'descricion',
                                'variable' => 'rotas[texto]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div id="rota-territorio" class="formulario-field">
                        <label for="territorio"><?php __e('Teritorio'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($territorios, array(
                                'id' => 'territorio',
                                'variable' => 'territorio',
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'class' => 'selector-despregable select-territorios w3',
                                'first_option' => '',
                                'data-placeholder' => __('Territorio'),
                                'data-selected' => $Vars->var['territorio'],
                                'data-child' => 'select.select-provincias',
                                'data-anchor' => 'rota-territorio'
                            ));
                            ?>
                        </div>
					</div>

					<div id="rota-provincia" class="formulario-field">
                        <label for="provincia"><?php __e('Provincia'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'id' => 'provincia',
                                'variable' => 'provincia',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'selector-despregable select-provincias w3',
                                'first_option' => '',
                                'data-placeholder' => __('Provincia'),
                                'data-selected' => $Vars->var['provincia'],
                                'data-child' => 'select.select-concellos',
                                'data-anchor' => 'rota-provincia'
                            ));
                            ?>
                        </div>
					</div>

					<div id="rota-concellos" class="formulario-field">
                        <label for="concello"><?php __e('Concello'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'id' => 'concello',
                                'variable' => 'concello',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'selector-despregable select-concellos w3',
                                'first_option' => '',
                                'data-placeholder' => __('Concello'),
                                'data-selected' => $Vars->var['concello'],
                                'data-anchor' => 'rota-concellos'
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
                                'variable' => 'rotas[lugar]',
                                'placeholder' => __('Lugar da rota'),
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="area"><?php __e('Área que abarca'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'area',
                                'variable' => 'rotas[area]'
                            ));
                            ?>
                        </div>
					</div>
                    
                    <div class="formulario-field">
                        <label for="kml"><?php __e('Arquivo kml'); ?></label>
                        <div>
                            <?php
                            echo $Form->file(array(
                                'variable' => 'rotas[kml]',
                                'id' => 'kml',
                                'data-text' => basename($rota['kml']),
                                'data-value' => ($rota['kml'] ? fileWeb('uploads|'  .$rota['kml'], false, true) : '')
                            ));
                            ?>
                        </div>
                    </div>
                </fieldset>

                <section class="erros-axuda ly-e2">
                    <p><strong><?php __e('Este contido será gardado en idioma %s', __('idioma-'.LANGUAGE)); ?></strong></p>
                    <br />
                    <p><?php echo __('axuda-formulario-rotas-contido'); ?></p>
                </section>
            </div>
            
			 <fieldset class="content wrapper ly-f1">
                <div class="content-mapa">
                    <div class="mapa"></div>
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
                            <button id="drawing-polyline" class="btn" type="button">
                                <?php __e('Rota'); ?>
                            </button>
                        </section>
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
                    
					<div class="formulario-field obrigatorio">
                        <label for="rotas[dificultade]"><?php __e('Dificultade'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($dificultades, array(
                                'id' => 'rotas[dificultade]',
                                'variable' => 'rotas[dificultade]',
                                'class' => 'selector-despregable w2',
                                'option_text_as_value' => true,
                                'first_option' => '',
                                'data-placeholder' => __('Selección obrigatoria'),
                                'required' => 'required'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field obrigatorio">
                        <label for="distancia"><?php __e('Distancia total (en metros)'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'distancia',
                                'class' => 'w3',
                                'variable' => 'rotas[distancia]',
                                'required' => 'required'
                            ));
                            ?>
                            <button id="calcular-distancia" class="btn boton-distancia" type="button">
                                <?php __e('Calcular'); ?>
                            </button>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="duracion"><?php __e('Duración (en minutos)'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'duracion',
                                'class' => 'w3',
                                'variable' => 'rotas[duracion]'
                            ));
                            ?>
                        </div>
					</div>

                    <div class="formulario-field">
                        <label for="especie"><?php __e('Especies interesantes que te podes atopar'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'especie',
                                'variable' => 'especies[url]',
                                'placeholder' => __('Especies interesantes que te podes atopar'),
                                'class' => 'listaxe-especies',
                                'data-multiple' => 'multiple',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['especies']))
                            ));
                            ?>
                        </div>
                    </div>
				</fieldset>
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
					<p><?php __e('axuda-formulario-rotas-imaxes'); ?></p>
				</section>
			</div>
            
            <?php if ($Acl->check('action', 'rota-validar')) { ?>
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field">
                        <label for="espazo-validado"><?php __e('Rota validada'); ?></label>
						<div>
							<?php
							echo $Form->checkbox(array(
								'variable' => 'rotas[validado]',
								'value' => '1',
								'checked' => ($rota['validado'] ? true : false),
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
                    <?php if ($rota['url'] && $Acl->check('action', 'rota-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('rota', $rota['url']).get('phpcan_action', 'rota-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

                    <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'name' => 'phpcan_action',
                        'id' => 'boton-gardar',
                        'value' => 'rota-gardar',
                        'class' => 'btn btn-highlight',
                        'text' => ('<i class="icon-save"></i> '.__('Gardar'))
                    ));
                    ?>

                    <a href="<?php echo $rota['url'] ? path('rota', $rota['url']) : path('rotas'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>
                </p>
			</fieldset>

            <div class="hidden overlay">
                <div class="overlay-background"></div>
                <div class="overlay-text">
                    <i class="icon-spinner icon-spin"></i> <?php __e('Calculando alturas'); ?>
                </div>
            </div>
		</form>
	</div>
</section>
